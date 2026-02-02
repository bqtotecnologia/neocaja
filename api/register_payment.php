<?php
$admitted_user_types = ['Estudiante', 'Super'];
include_once '../utils/Auth.php';
session_start();

$error = '';
if(Auth::UserLevelIn($admitted_user_types) === false)
    $error = 'Permiso denegado. Cierre sesión e inicie nuevamente';

if($error === ''){
    $availablePaymentMethods = ['mobile_payment', 'transfer'];
    $inputJSON = file_get_contents('php://input');
    $post = json_decode($inputJSON, TRUE);
    
    if($post === NULL){
        $error = 'POST vacío';
    }
}

if($error === ''){
    if(
        !isset($post['cedula']) || 
        !isset($post['document']) ||
        !isset($post['ref']) ||
        !isset($post['price']) ||
        !isset($post['payment_method_type']) ||
        !isset($post['payment_method']) ||
        !isset($post['codes'])
    ){
        $error = 'Campos necesarios no recibidos';
    }
}

if($error === ''){
    include_once '../utils/Validator.php';
    foreach($post as $key => $value){
        if($key === 'codes') 
            continue;
    
        $suspicious = Validator::HasSuspiciousCharacters($value);
        if($suspicious === true){
            $error = 'Campo ' . $key . ' inválido';
            break;
        }
    }
}

if($error === ''){
    if(count($post['codes']) < 1){
        $error = 'No se seleccionaron productos';
    }else{
        foreach($post['codes'] as $code){
            $valid = Validator::HasSuspiciousCharacters($code);
            if($valid === true)
                $error = 'Texto malicioso detectado en algún producto';
        }
    }
}

if($error === ''){
    include_once '../models/account_model.php';
    $account_model = new AccountModel();
    $target_account = $account_model->GetAccountByCedula($post['cedula']);
    if($target_account === false)
        $error = 'Cuenta no encontrada';
}

if($error === ''){
    if($target_account['cedula'] !== $_SESSION['neocaja_cedula'])
        $error = 'Acción denegada';
}

if($error === ''){
    include_once '../models/product_model.php';
    include_once '../models/siacad_model.php';
    $product_model = new ProductModel();
    $siacad = new SiacadModel();
    $period = $siacad->GetCurrentPeriodo()['idperiodo'];
    $products = $product_model->GetAvailableProductsOfStudent($target_account['cedula'], $period);
    if($products === false)
        $error = 'Ocurrió un error al intentar obtener los productos disponibles';
}

if($error === ''){
    $final_products = [];
    foreach($products as $product){
        if(in_array($product['code'], $post['codes']))
            array_push($final_products, $product);
    }

    $productCounts = count($final_products);
    if($productCounts === 0)
        $error = 'No se encontraron los productos seleccionados';
}

if($error === ''){
    if(!in_array($post['payment_method_type'], $availablePaymentMethods))
        $error = 'Método de pago inválido';
}

if($error === ''){
    if($post['payment_method_type'] === 'mobile_payment'){
        include_once '../models/mobile_payments_model.php';
        $mobile_payment_model = new MobilePaymentsModel();
        $target_payment_method = $mobile_payment_model->GetMobilePayment($post['payment_method']);
        if($target_payment_method === false)
            $error = 'Pago móvil seleccionado no encontrado';
    }
    else if($post['payment_method_type'] === 'transfer'){
        include_once '../models/transfers_model.php';
        $transfer_model = new TransfersModel();
        $target_payment_method = $transfer_model->GetTransfer($post['payment_method']);
        if($target_payment_method === false)
            $error = 'Cuenta de transferencia seleccionada no encontrada';
    }
}

if($error === ''){
    // Verificamos si seleccionó FOC
    $hasFOC = false;    
    $youngestPayableMonth = null;
    $product_total = 0;

    foreach($final_products as $product){
        if($product['name'] === 'FOC'){
            $hasFOC = true;
        }
        else if($youngestPayableMonth === null)
            $youngestPayableMonth = $product['month'];

        $product_total += $product['price'];
    }

    // Validamos que haya escogido meses consecutivos empezando por el primero
    $nextMonth = intval($youngestPayableMonth);
    $consecutiveMonths = 0;
    $cyclesMade = -1;    

    while(true){
        $cyclesMade++;
        foreach($final_products as $product){
            if(intval($product['month']) === $nextMonth && $product['name'] !== 'FOC'){
                $nextMonth++;
                if($nextMonth === 13)
                    $nextMonth = 1;
                
                $consecutiveMonths++;
                break;
            }
        }

        if($cyclesMade > $productCounts)
            break;
    }

    if($hasFOC){
            if($productCounts !== $consecutiveMonths + 1)
                $error = 'Debes seleccionar meses consecutivos y empezar por el primero';
        }
        else{
            if($productCounts !== $consecutiveMonths)
                $error = 'Debes seleccionar meses consecutivos y empezar por el primero';
        }
}

if($error === ''){
    // Verificamos el monto
    include_once '../models/coin_model.php';
    $coin_model = new CoinModel();

    $usd = $coin_model->GetCoinByName('Dólar');
    $total_bs = round($product_total * $usd['price'], 2);

    // Lo comparamos con un margen de error de 0.2bs debido al cálculo de decimales
    if((($total_bs - 0.2 < $post['price']) && ($total_bs + 0.2 > $post['price'])) === false)
        $error = 'El monto no coincide';   
}

$created = false;
if($error === ''){
    // Registramos el pago
    include_once '../models/account_payments_model.php';
    $payments_model = new AccountPaymentsModel();
    $insertData = [
        'related_id' => $target_account['id'],
        'related_with' => 'client',
        'payment_method_type' => $post['payment_method_type'],
        'payment_method' => $post['payment_method'],
        'price' => $post['price'],
        'ref' => $post['ref'],
        'document' => $post['document'],
        'state' => 'Por revisar',
    ];

    $created = $payments_model->CreatePayment($insertData);
    if($created === false)
        $error = 'Ocurrió un error al intentar crear el pago';
}

if($error === ''){
    // Registramos los productos del pago
    foreach($final_products as $product){
        $success = $payments_model->AddProductToPayment($product, $created['id']);
        if($success === false){
            $error = 'Ocurrió un error al registrar uno de los productos';
            break;
        }
    }
}

if($error === ''){
    $action = 'El cliente ' . $target_account['names'] . ' ' . $target_account['surnames'] . ' (' . $target_account['cedula'] . ') ';
    $action .= 'registró un pago remoto. referencia:' . $insertData['ref'] . ', monto:' . $insertData['price'];
    $action .= ', cedula/rif:' . $insertData['document'] . ', metodo de pago:' . $insertData['payment_method_type'];
    $payments_model->CreateBinnacle('NULL', $action);
    $response = [
        'status' => true,
        'message' => 'Su compra ha sido registrada con éxito. Acuda al instituto para retirar su factura.'
    ];
}else{
    $response = [
        'status' => false,
        'message' => $error
    ];
}

if($error !== '' && $created !== false){
    $payments_model->DeletePayment($created['id']);
}

$json_data = json_encode($response, JSON_UNESCAPED_UNICODE); // Para que acepte las tildes
header('Content-Type: application/json');
echo $json_data;
exit;
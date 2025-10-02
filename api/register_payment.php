<?php
include_once '../utils/Auth.php';
$admitted_user_types = ['Estudiante', 'Super'];
session_start();
$userOk = Auth::UserLevelIn($admitted_user_types);
$error = '';
if($userOk === false)
    $error = 'Permiso denegado. Cierre sesión e inicie nuevamente';

include_once '../utils/Validator.php';

$availablePaymentMethods = ['mobile_payment', 'transfer'];
$inputJSON = file_get_contents('php://input');
$post = json_decode($inputJSON, TRUE);




if($post === NULL){
    $error = 'POST vacío';
}

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



foreach($post as $key => $value){
    if($key === 'codes') 
        continue;

    $suspicious = Validator::HasSuspiciousCharacters($value);
    if($suspicious === true){
        $error = 'Campo ' . $key . ' inválido';
        break;
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

if($error === 0){
    // Verificamos si seleccionó FOC
    $hasFOC = false;    
    $youngestPayableMonth = null;
    foreach($final_products as $product){
        if($product['name'] === 'FOC'){
            $hasFOC = true;
        }
        else if($youngestPayableMonth === null)
            $youngestPayableMonth = $product['month'];
    }

    // Validamos que haya escogido meses consecutivos empezando por el primero
    $nextMonth = intval($youngestPayableMonth);
    $consecutiveMonths = 0;
    $cyclesMade = -1;

    while(true){
        $cyclesMade++;
        foreach($final_products as $product){
            if(intval($product['month']) === $nextMonth){
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
                $error = 'Debes seleccionar meses consecutivos';
        }
        else{
            if($productCounts !== $consecutiveMonths)
                $error = 'Debes seleccionar meses consecutivos';
        }
}


$created = false;
if($error === ''){
    // Registramos el pago
    include_once '../models/account_payments_model.php';
    $payments_model = new AccountPaymentsModel();
    $insertData = [
        'account' => $target_account['id'],
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
<?php
$admitted_user_types = ['Estudiante'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';
include_once '../utils/Auth.php';

$error = '';

if(empty($_POST))
    $error = 'POST vacío';

if($error === ''){
    include_once '../fields_config/remote_payments.php';
    $cleanData = Validator::ValidatePOSTFields($remotePaymentFields);
    if(is_string($cleanData))
        $error = $cleanData;
}

if($error === ''){
    include_once '../models/account_model.php';
    $account_model = new AccountModel();
    $target_account = $account_model->GetAccountByCedula($_SESSION['neocaja_cedula']);
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
        if(in_array($product['code'], $_POST['codes']))
            array_push($final_products, $product);
    }

    $productCounts = count($final_products);
    if($productCounts === 0)
        $error = 'No se encontraron los productos seleccionados';
}

if($error === ''){
    $availablePaymentMethods = ['mobile_payment', 'transfer'];
    if(!in_array($cleanData['payment_method_type'], $availablePaymentMethods))
        $error = 'Método de pago inválido';
}

if($error === ''){
    if(!isset($_FILES['capture']))
        $error = 'No se encontró ninguna imagen del comprobante de pago';
}

if($error === ''){
    $target_image = $_FILES['capture'];
    
    $imageMaxSize = 1024 * 1024; // 1Mb
    if($target_image['size'] >= $imageMaxSize){
        $error = 'La imagen seleccionada supera el límite de tamaño (1Mb).';
    }
}

if($error === ''){
    $timezone = new DateTimeZone('America/Caracas');
    $now = new DateTime('now', $timezone);
    $uploaddir = '../images/payments_captures/';

    $splits = explode('.', $target_image['name']);
    $imageFormat = $splits[count($splits) - 1];
    $newImageName = date_timestamp_get($now) . '.' . $imageFormat;
    $uploadPath = $uploaddir . $newImageName;
    
    if (!move_uploaded_file($target_image['tmp_name'], $uploadPath))
        $error = 'Ocurrió un error durante la subida del archivo';
}

if($error === ''){
    if($cleanData['payment_method_type'] === 'mobile_payment'){
        include_once '../models/mobile_payments_model.php';
        $mobile_payment_model = new MobilePaymentsModel();
        $target_payment_method = $mobile_payment_model->GetMobilePayment($cleanData['payment_method']);
        if($target_payment_method === false)
            $error = 'Pago móvil seleccionado no encontrado';
    }
    else if($cleanData['payment_method_type'] === 'transfer'){
        include_once '../models/transfers_model.php';
        $transfer_model = new TransfersModel();
        $target_payment_method = $transfer_model->GetTransfer($cleanData['payment_method']);
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

    $cleanData['price'] = str_replace('.', '', $cleanData['price']);
    $cleanData['price'] = floatval(str_replace(',', '.', $cleanData['price']));

    // Lo comparamos con un margen de error de 0.2bs debido al cálculo de decimales
    if((($total_bs - 0.2 < $cleanData['price']) && ($total_bs + 0.2 > $cleanData['price'])) === false)
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
        'payment_method_type' => $cleanData['payment_method_type'],
        'payment_method' => $cleanData['payment_method'],
        'price' => $cleanData['price'],
        'ref' => $cleanData['ref'],
        'capture' => $newImageName,
        'document' => $cleanData['document'],
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
        'message' => 'Su compra ha sido registrada con éxito. Actualmente se encuentra en estado "Por revisar".'
    ];
}else{
    $response = [
        'status' => false,
        'message' => $error
    ];
}

if($error !== '' && $created !== false){
    $payments_model->DeletePayment($created['id']);
    unlink($uploadPath);
}

if($error === ''){
    $message = 'Su compra ha sido registrada con éxito. Actualmente se encuentra en estado "Por revisar".';
    header("Location: $base_url/views/panel.php?message=$message");
}
else{
    header("Location: $base_url/views/forms/pay_form.php?error=$error");
}

exit;
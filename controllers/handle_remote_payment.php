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
    $today = new DateTime(date('Y-m-d'), new DateTimeZone('America/Caracas'));
    if($cleanData['date'] > $today)
        $error = 'La fecha del pago debe ser anterior o igual a la fecha de hoy';
}

if($error === ''){
    include_once '../models/product_model.php';
    include_once '../models/siacad_model.php';
    $product_model = new ProductModel();
    $siacad = new SiacadModel();
    $period = $siacad->GetCurrentPeriodo()['idperiodo'];
    $periodProducts = $product_model->GetAvailableProductsOfStudentByPeriod($target_account['cedula'], $period);
    if($periodProducts === false)
        $error = 'Ocurrió un error al intentar obtener los productos disponibles';
}

if($error === ''){
    $product_total = 0;
    $final_products = [];
    foreach($periodProducts as $period => $products){
        foreach($products as $product){
            if(in_array($product['code'], $_POST['codes'])){
                array_push($final_products, $product);
                $product_total += intval($product['price']);
            }
        }
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
$focCount = 0;
if($error === ''){
    // Comprovamos si seleccionó FOC
    $youngestPayableMonths = [];
    $periodProductsCount = [];
    $periodNames = '';

    foreach($periodProducts as $period => $products){
        $periodNames .= "'" . $period . "', ";

        foreach($products as $product){
            if(strpos($product['name'], 'FOC') !== false){
                $focCount += 1;
            }
            else{
                if(!isset($youngestPayableMonths[$product['period']]))
                    $youngestPayableMonths[$product['period']] = $product['month'];

                if(!isset($periodProductsCount[$product['period']]))
                    $periodProductsCount[$product['period']] = 0;

                $periodProductsCount[$product['period']] += 1;
            }
        }

    }

    $periodNames = trim($periodNames, ', ');
    $periods = $siacad->GetPeriodsByNames($periodNames);

    $consecutiveMonths = 0;

    foreach($periods as $period){
        // Por cada uno de los periodos, validamos que haya escogido meses consecutivos empezando por el primero
        $nextMonth = intval($youngestPayableMonths[$period['nombreperiodo']]);
        $cyclesMade = -1;
        while(true){
            $cyclesMade += 1;
            for($i = 0; $i < $productCounts; $i++){
                $product = $final_products[$i];
                if(
                    $product['period'] !== $period['nombreperiodo'] ||
                    strpos($product['name'], 'FOC') !== false
                )
                    continue;

                if(intval($product['month']) === $nextMonth){
                    $nextMonth += 1;
                    if($nextMonth === 13)
                        $nextMonth = 1;
                    
                    $consecutiveMonths += 1;
                    break;
                }
            }

            if($cyclesMade > $productCounts)
                break;
        }         
    } 

    if($productCounts !== $consecutiveMonths + $focCount)
        $error = 'Debes seleccionar meses consecutivos y empezar por el primero';
}

// Validar que haya pagado por completo un periodo anterior para intentar pagar el siguiente
if($error === ''){
    $selectedProductsCount = [];
    // Contamos los productos seleccionado de cada periodo  
    foreach($final_products as $product){
        if(!isset($selectedProductsCount[$product['period']]))
            $selectedProductsCount[$product['period']] = 0;

        $selectedProductsCount[$product['period']] += 1;
    }

    // Los comparamos con el total de productos de cada periodo por orden
    $canPayNextPeriod = true;
    for($i = 0; $i < count($periods); $i++){
        $period = $periods[$i]['nombreperiodo'];

        if(!isset($selectedProductsCount[$period]))
            continue;

        if($canPayNextPeriod === false && $selectedProductsCount[$period] > 0){
            $error = 'Debes pagar por completo un periodo anterior para empezar a pagar el siguiente.';
            break;
        }

        if($periodProductsCount[$period] === $selectedProductsCount[$period])
            continue;
        
        if($selectedProductsCount[$period] < $periodProductsCount[$period]){
            if($canPayNextPeriod)
                $canPayNextPeriod = false;
            continue;
        }
    }
}

if($error === ''){
    // Verificamos el monto
    include_once '../models/coin_model.php';
    $coin_model = new CoinModel();

    $usd = $coin_model->GetCoinPriceOfDateByName('Dólar', $cleanData['date']->format('Y-m-d'));
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
    include_once '../models/remote_payments_model.php';
    $payments_model = new RemotePaymentsModel();
    $insertData = [
        'related_id' => $target_account['id'],
        'related_with' => 'client',
        'payment_method_type' => $cleanData['payment_method_type'],
        'payment_method' => $cleanData['payment_method'],
        'price' => $cleanData['price'],
        'ref' => $cleanData['ref'],
        'date' => $cleanData['date']->format('Y-m-d'),
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
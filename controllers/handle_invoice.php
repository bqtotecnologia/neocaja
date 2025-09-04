<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';

include_once '../utils/base_url.php';
include_once '../utils/Validator.php';

$error = '';

if(empty($_POST)){
    $error = 'POST vacío';
}

$fields_config = [
    'invoice_number' => [
        'min' => 1,
        'max' => 11,
        'required' => true,
        'type' => 'integer',
        'suspicious' => true,
    ],
    'control_number' => [
        'min' => 1,
        'max' => 11,
        'required' => true,
        'type' => 'integer',
        'suspicious' => true,
    ],
    'account' => [
        'min' => 1,
        'max' => 11,
        'required' => true,
        'type' => 'integer',
        'suspicious' => true,
    ],
    'rate-date' => [
        'min' => 8,
        'max' => 10,
        'required' => true,
        'type' => 'date',
        'suspicious' => false,
    ],
    'observation' => [
        'min' => 0,
        'max' => 255,
        'required' => false,
        'type' => 'string',
        'suspicious' => true,
    ]
];

$result = Validator::ValidatePOSTFields($fields_config);
if(is_string($result))
    $error = $result;
else
    $cleanData = $result;


if($error === ''){
    include_once '../models/invoice_model.php';
    $invoice_model = new InvoiceModel();

    $invoice_number = $cleanData['invoice_number'];
    $control_number = $cleanData['control_number'];
    $allOK = false;


    while(true){
        $invoice_number_exists = $invoice_model->GetInvoiceByInvoiceNumber($invoice_number);
        $control_number_exists = $invoice_model->GetInvoiceByControlNumber($control_number);

        if($invoice_number_exists !== false)
            $invoice_number++;

        if($control_number_exists !== false)
            $control_number++;
    
        if([$invoice_number_exists, $control_number_exists] === [false, false]){
            break;
        }        
    }
}

if($error === ''){
    $cleanData['invoice_number'] = $invoice_number;
    $cleanData['control_number'] = $control_number;

    include_once '../models/account_model.php';
    $account_model = new AccountModel();

    $target_account = $account_model->GetAccount($cleanData['account']);
    if($target_account === false)
        $error = 'Cliente no encontrado';
}

if($error === ''){
    include_once '../models/siacad_model.php';
    $siacad = new SiacadModel();
    $period = $siacad->GetCurrentPeriodo();
    if($cleanData['observation'] === '')
        $cleanData['observation'] = 'NULL';
    else
        $cleanData['observation'] = "'" . $cleanData['obaservation'] . "'";
    
    $target_invoice = $invoice_model->CreateInvoice($cleanData, strval($period['idperiodo']));
    if($target_invoice === false)
        $error = 'Hubo un error al intentar crear la factura';
}

if($error === ''){
    $last_product_number = 0;
    $last_payment_method_number = 0;

    foreach($_POST as $key => $value){
        if(strpos($key,'product' ) !== false){
            $number = explode('-', $key)[2];
            $last_product_number = intval($number);
        }

        if(strpos($key,'payment' ) !== false){
            $number = explode('-', $key)[2];
            $last_payment_method_number = intval($number);
        }
    }
    
    include_once '../models/product_model.php';
    $product_model = new ProductModel();
    
    $concepts = [];    
    // Recorriendo y ordenando los productos
    for ($i=1; $i <= $last_product_number; $i++) { 
        if(!isset($_POST['product-id-' . $i]))
            continue;

        $product_id = $_POST['product-id-' . $i];
        $target_product = $product_model->GetProduct($product_id);
        if($target_product === false){
            $error = "Producto de id $product_id no encontrado";
            break;
        }

        // Se intentó registrar una mensualidad sin mes
        if($_POST["product-month-$i"] === '' && $target_product['name'] === 'Mensualidad'){
            $error = 'Se seleccionó el producto mensualidad sin especificar el mes';
            break;
        }

        $to_add = [
            'price' => $_POST["product-baseprice-$i"],
            'history_id' => $target_product['history_id'],
            'month' => $_POST["product-month-$i"] === '' ? 'NULL' : $_POST["product-month-$i"],
            'complete' => isset($_POST["product-complete-$i"]) ? '1' : '0',
        ];

        array_push($concepts, $to_add);
    }    
}

if($error === ''){
    if(count($concepts) === 0)
        $error = 'Se debe escoger al menos un concepto';
}

if($error === ''){
    include_once '../models/coin_model.php';
    include_once '../models/bank_model.php';
    include_once '../models/sale_point_model.php';
    include_once '../models/payment_method_model.php';

    $coin_model = new CoinModel();
    $bank_model = new BankModel();
    $sale_point_model = new SalePointModel();
    $payment_method_model = new PaymentMethodModel();

    $payment_methods = [];
    // Recorriendo y ordenando los métodos de pago    
    for ($i=1; $i <= $last_payment_method_number; $i++) { 
        if(!isset($_POST['payment-method-' . $i]))
            continue;
        
        $payment_method_id = $_POST['payment-method-' . $i];
        
        $target_payment_method = $payment_method_model->GetPaymentMethodType($payment_method_id);
        if($target_payment_method === false){
            $error = "Método de pago de id $payment_method_id no encontrado";
            break;
        }

        $coin_id = $_POST['payment-coin-' . $i];
        $target_coin = $coin_model->GetCoin($coin_id);
        if($target_coin === false){
            $error = "Moneda de id $coin_id no encontrada";
            break;
        }
        else{
            $rate_exists = $coin_model->GetCoinPriceOfDate($target_coin['id'], $cleanData['rate-date']->format('Y-m-d'));
            if($rate_exists === false && $target_coin['name'] !== 'Bolívar')
                $error = 'No existe una tasa para la moneda ' . $target_coin['name'] . ' en la fecha ' . $cleanData['rate-date']->format('d/m/Y');
        }

        $bank_id = $_POST['payment-bank-' . $i];
        if($bank_id !== ''){
            $target_bank = $bank_model->GetBankById($bank_id);
            if($target_bank === false){
                $error = "Banco de id $bank_id no encontrado";
                break;
            }
        }        
        else
            $target_bank = null;
        
        $sale_point_id = $_POST['payment-salepoint-' . $i];
        if($sale_point_id !== ''){
            $target_sale_point = $sale_point_model->GetSalePointByCode($sale_point_id);
            if($target_sale_point === false){
                $error = "Punto de venta de código $sale_point_id no encontrado";
                break;
            }   
        }
        else
            $target_sale_point = null;

        $to_add = [
            'method' => $target_payment_method['id'],
            'coin' => $target_coin['history_id'],
            'salepoint' => $target_sale_point['id'] ?? 'NULL',
            'bank' => $target_bank['id'] ?? 'NULL',
            'document_number' => $_POST["payment-document-$i"] === '' ? 'NULL' : ("'" . $_POST["payment-document-$i"] . "'"),
            'price' => $_POST["payment-price-$i"] ?? 'NULL',
            'igtf' => '0',
        ];

        array_push($payment_methods, $to_add);
    }
}

// Processing the IGTF
if($error === '' && isset($_POST['igtf-total'])){
    $payment_method_id = $_POST['igtf-method'];
        
    $target_payment_method = $payment_method_model->GetPaymentMethodType($payment_method_id);
    if($target_payment_method === false){
        $error = "Método de pago de id $payment_method_id no encontrado";
    }

    $coin_id = $_POST['igtf-coin'];
    $target_coin = $coin_model->GetCoin($coin_id);
    if($target_coin === false){
        $error = "Moneda de id $coin_id no encontrada";
    }

    $bank_id = $_POST['igtf-bank'];
    if($bank_id !== ''){
        $target_bank = $bank_model->GetBankById($bank_id);
        if($target_bank === false){
            $error = "Banco de id $bank_id no encontrado";
        }
    }        
    else
        $target_bank = null;
    
    $sale_point_id = $_POST['igtf-salepoint'];
    if($sale_point_id !== ''){
        $target_sale_point = $sale_point_model->GetSalePointByCode($sale_point_id);
        if($target_sale_point === false){
            $error = "Punto de venta de código $sale_point_id no encontrado";
        }   
    }
    else
        $target_sale_point = null;
        
}

if($error === ''){
    $to_add = [
        'method' => $_POST['igtf-method'],
        'coin' => $_POST['igtf-coin'],
        'salepoint' => $_POST["igtf-salepoint"] === '' ? 'NULL' : ("'" . $_POST["igtf-salepoint"] . "'"),
        'bank' => $_POST['igtf-bank'] ?? 'NULL',
        'document_number' => $_POST["igtf-document"] === '' ? 'NULL' : ("'" . $_POST["igtf-document"] . "'"),
        'price' => $_POST["igtf-price"] ?? 'NULL',
        'igtf' => '1',
    ];
    
    array_push($payment_methods, $to_add);
}

if($error === ''){
    if(count($payment_methods) === 0)
        $error = 'Se debe escoger al menos un método de pago';
}

if($error === ''){
    // Agregamos los conceptos de la factura
    foreach($concepts as $concept){
        $created = $invoice_model->AddConceptToInvoice($concept, strval($target_invoice['id']));
        if($created === false){
            $error = 'Ocurrió un error al intentar agregar el concepto del producto de monto ' . $concept['price'];
            break;
        }
    }
}

if($error === ''){
    // Agregamos los conceptos de la factura
    foreach($payment_methods as $payment_method){
        $created = $invoice_model->AddPaymentMethodToInvoice($payment_method, strval($target_invoice['id']));
        if($created === false){
            $error = 'Ocurrió un error al intentar agregar el método de pago de monto ' . $payment_method['price'];
            break;
        }
    }
}


if($error === ''){
    $action = "Creó la factura Nº $invoice_number, Numero de control: $control_number. Al cliente " . $target_account['names'] . ' ' . $target_account['surnames'] . ' de cédula ' . $target_account['cedula'];
    $invoice_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
    $message = 'Factura creada correctamente';
    $redirect = $base_url . '/views/panel.php?message=' . $message;
}
else{
    if(isset($target_invoice)){
        if($target_invoice !== false){
            $invoice_model->DeleteInvoice($target_invoice['id']);
            $redirect = $base_url . '/views/forms/invoice_form.php?error=' . $error;
        }
    }
}

header('Location: ' . $redirect);
exit;
<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';
include_once '../utils/months_data.php';

$error = '';

if(empty($_POST)){
    $error = 'POST vacío';
}

$payment_method_field_block = [
    'Efectivo' => ['bank', 'salepoint', 'document'],
    'Pago móvil' => ['salepoint'],
    'Transferencia' => ['salepoint'],
    'Tarjeta de déb' => [],
];

include_once '../fields_config/invoices.php';
$result = Validator::ValidatePOSTFields($invoiceFields);
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

if($error === '' && $_POST['known-income'] !== ''){
    include_once '../models/remote_payments_model.php';
    $remote_payments_model = new RemotePaymentsModel();

    $target_payment = $remote_payments_model->GetAccountPayment($cleanData['known-income']);
    if($target_payment === false)
        $error = 'El ingreso identificado seleccionado no pudo ser encontrado';
}

if($error === ''){
    include_once '../models/siacad_model.php';
    $siacad = new SiacadModel();
    $period = $siacad->GetCurrentPeriodo();
    if($cleanData['observation'] === '')
        $cleanData['observation'] = 'NULL';
    else
        $cleanData['observation'] = "'" . $cleanData['obaservation'] . "'";

    $cleanData['account'] = $target_account['account_history_id'];
    
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

    $searched_product_names = [
        'Mensualidad',
        'Diferencia Mensualidad',
        'Saldo Mensualidad',
        'Abono Mensualidad',
        'FOC',
    ];
    
    include_once '../models/product_model.php';
    $product_model = new ProductModel();
    
    // Para validar que el orden de los meses sea correcto, primero crearemos una estructura dentro de $cashierIntents (intenciones del cajero)
    // Dicha variable contendrá {'periodo': 'months': {'mes1': {paid, debt, partial, remaining}, 'mes2'...}, foc: ...}
    // paid especifica si el cajero está intentando cobrarle el mes al estudiante (Mensualidad)
    // debt especifica si el cajero está intentando cobrarle mora al estudiante (Diferencia mensualidad)
    // partial especifica si el cajero está intentando cobrarle el mes al estudiante luego de que este abonara (Abono mensualidad)
    // remaining especifica si el cajero está intentando cobrarle lo que le falta para pagar el mes (Saldo Mensualidad)
    $cashier_intents = [];


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

        // Se intentó registrar un concepto relacionado a una mensualidad sin mes especificar el mes
        if($_POST["product-month-$i"] === '' && strpos($target_product['name'], 'Mensualidad')  !== false){
            $error = 'Se seleccionó el producto relacionado a la mensualidad sin especificar el mes';
            break;
        }

        $monthNumber = intval($_POST["product-month-$i"]);
        $period = $_POST["product-period-$i"];

        if(in_array($target_product['name'], $searched_product_names)){
            // Es un concepto relacionado a una mensualidad o un FOC, lo añadimos para validarlo más tarde

            $monthName = $month_translate[$monthNumber];

            if(!isset($cashierIntents[$period]))
                $cashierIntents[$period] = ['months' => [], 'foc' => 0];

            if(!isset($cashierIntents[$period]['months'][$monthName]))
                $cashierIntents[$period]['months'][$monthName] = ['paid' => 0, 'debt' => 0, 'partial' => 0, 'remaining' => 0];
            
            if($target_product === 'Mensualidad')
                $cashierIntents[$period]['months'][$monthName]['paid'] = 1;

            if($target_product === 'Diferencia Mensualidad')
                $cashierIntents[$period]['months'][$monthName]['debt'] = 1;

            if($target_product === 'Abono Mensualidad')
                $cashierIntents[$period]['months'][$monthName]['partial'] = 1;        

            if($target_product === 'Saldo Mensualidad')
                $cashierIntents[$period]['months'][$monthName]['remaining'] = 1;

            if($target_product === 'FOC')
                $cashierIntents[$period]['foc'] = 1;
        }

        $to_add = [
            'price' => $_POST["product-baseprice-$i"],
            'history_id' => $target_product['history_id'],
            'month' => $_POST["product-month-$i"] === '' ? 'NULL' : $_POST["product-month-$i"],
            'period' => $_POST["product-period-$i"] === '' ? 'NULL' : $_POST["product-period-$i"],
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

        $target_bank = null;
        $target_sale_point = null;
        $target_document = null;
        
        $payment_method_id = $_POST['payment-method-' . $i];

        $target_price = $_POST["payment-price-$i"];

        if($target_price === '')
            continue;

        if(!is_numeric($target_price)){
            $error = 'El precio de un método de pago es inválido';
            break;
        }
        
        $target_payment_method = $payment_method_model->GetPaymentMethodType($payment_method_id);
        if($target_payment_method === false){
            $error = "Método de pago de id $payment_method_id no encontrado";
            break;
        }

        $blocked_fields = $payment_method_field_block[$target_payment_method['name']];

        $coin_id = $_POST['payment-coin-' . $i];
        $target_coin = $coin_model->GetCoin($coin_id);
        if($target_coin === false){
            $error = "Moneda de id $coin_id no encontrada";
            break;
        }
        else{
            $rate_exists = $coin_model->GetCoinPriceOfDateById($target_coin['id'], $cleanData['rate-date']->format('Y-m-d'));
            if($rate_exists === false && $target_coin['name'] !== 'Bolívar')
                $error = 'No existe una tasa para la moneda ' . $target_coin['name'] . ' en la fecha ' . $cleanData['rate-date']->format('d/m/Y');
        }

        if(!in_array('bank', $blocked_fields)){
            if(!isset($_POST['payment-bank-' . $i])){
                $error = 'No se recibió ningún banco en uno de los métodos de pago';
                break;
            }
            
            $bank_id = $_POST['payment-bank-' . $i];
            if($bank_id !== ''){
                $target_bank = $bank_model->GetBankById($bank_id);
                if($target_bank === false){
                    $error = "Banco de id $bank_id no encontrado";
                    break;
                }
            }else
                $error = 'El banco es requerido en uno de los métodos de pago';
        }

        if(!in_array('salepoint', $blocked_fields)){
            if(!isset($_POST['payment-salepoint-' . $i])){
                $error = 'No se recibió ningún punto de venta en uno de los métodos de pago';
                break;
            }
            
            $sale_point_id = $_POST['payment-salepoint-' . $i];
            if($sale_point_id !== ''){
                $target_sale_point = $sale_point_model->GetSalePoint($sale_point_id);
                if($target_sale_point === false){
                    $error = "Punto de venta de id $sale_point_id no encontrado";
                    break;
                }   
            }
            else
                $error = 'El punto de venta es requerido en uno de los métodos de pago';
        }       
        
        if($target_sale_point !== null){
            if($target_bank === null){
                $error = 'El banco es obligatorio si se seleccionó un punto de venta';
                break;
            }
                
            if(intval($target_sale_point['bank_id']) !== intval($target_bank['id'])){
                $error = 'El banco seleccionado no coicide con el punto de venta escogido';
                break;
            }
        }

        if(!in_array('document', $blocked_fields)){            
            if(!isset($_POST['payment-document-' . $i])){
                $error = 'No se recibió ningún número de documento en uno de los métodos de pago';
                break;
            }

            $target_document = $_POST["payment-document-$i"];            
            if($target_document === ''){
                $error = 'El número de documento es necesario';
                break;
            }
            
            if(Validator::HasSuspiciousCharacters($target_document)){
                $error = 'Uno de los números de documento ingresados tiene caracteres sospechosos';
                break;
            }
        }        

        $to_add = [
            'method' => $target_payment_method['id'],
            'coin' => $target_coin['history_id'],
            'salepoint' => $target_sale_point['id'] ?? 'NULL',
            'bank' => $target_bank['id'] ?? 'NULL',
            'document_number' => ("'" . $target_document . "'") ?? 'NULL',
            'price' => $target_price,
            'igtf' => '0',
        ];

        array_push($payment_methods, $to_add);
    }
}

// Processing the IGTF
while(true){
    if($error === '' && isset($_POST['igtf-price'])){
        $payment_method_id = $_POST['igtf-method'];
            
        $target_payment_method = $payment_method_model->GetPaymentMethodType($payment_method_id);
        if($target_payment_method === false){
            $error = "Método de pago de id $payment_method_id no encontrado para el IGTF";
            break;
        }

        $target_price = $_POST["igtf-price"];
        if($target_price === '')
            continue;

        if(!is_numeric($target_price)){
            $error = 'El precio del método de pago del IGTF es inválido';
            break;
        }
    
        $target_bank = null;
        $target_sale_point = null;
        $target_document = null;
    
        $blocked_fields = $payment_method_field_block[$target_payment_method['name']];
    
        $coin_id = $_POST['igtf-coin'];
        $target_coin = $coin_model->GetCoin($coin_id);
        if($target_coin === false){
            $error = "Moneda de id $coin_id no encontrada para el IGTF";
            break;
        }    
    
        if(!in_array('bank', $blocked_fields)){
            if(!isset($_POST['igtf-bank'])){
                $error = 'No se recibió el banco del IGTF';
                break;
            }
    
            $bank_id = $_POST['igtf-bank'];
            if($bank_id !== ''){
                $target_bank = $bank_model->GetBankById($bank_id);
                if($target_bank === false){
                    $error = "Banco de id $bank_id no encontrado en el IGTF";
                    break;
                }
            } 
            else
                $error = 'El banco del IGTF es necesario';
        }
        
        if(!in_array('salepoint', $blocked_fields)){
            if(!isset($_POST['igtf-salepoint'])){
                $error = 'No se recibió el punto de venta del IGTF';
                break;
            }

            $sale_point_id = $_POST['igtf-salepoint'];
            if($sale_point_id !== ''){
                $target_sale_point = $sale_point_model->GetSalePointByCode($sale_point_id);
                if($target_sale_point === false){
                    $error = "Punto de venta de código $sale_point_id no encontrado en el IGTF";
                    break;
                }   
            }
            else
                $error = 'El punto de venta del IGTF es necesario';
        }
        
        if(!in_array('document', $blocked_fields)){
            if(!isset($_POST['igtf-document'])){
                $error = 'No se recibió el numero de documento del IGTF';
                break;
            }

            $target_document = $_POST['igtf-document'];
            if($target_document === ''){
                $error = 'El numero de cocumento del IGTF es necesario';
                break;
            }

            if(Validator::HasSuspiciousCharacters($target_document)){
                $error = 'El numero de documento del IGTF contiene caracteres sospechosos';
                break;
            }
        }
    }

    break;
}

if($error === ''){
    if(count($cashierIntents) > 0){
        $periods = $siacad->GetPeriodsOfStudent($target_account['cedula']);
        $debtData = [];

        foreach($periods as $period){
            $account_state = $invoice_model->GetDebtOfAccountOfPeriod($target_account['cedula'], $period['idperiodo']);
            $debtData[$period['nombreperiodo']] = $account_state;
        }

        $debtData = array_reverse($debtData);
        // Si hay información dentro es que se ha recibido algún producto relacionado con mensualidades o FOC
        // Por lo tanto ahora debemos contrastar las intenciones del cajero con el estado de cuenta del estudiante
        // Y así poder comprobar que no esté intentando cobrarle un mes por adelantado o una diferencia mensualidad donde no la hay

        /*
        if(Object.keys(cashierIntents).length > 0){
           
        
            var myPeriods = availablePeriods.slice().reverse() // Los periodos ordenados del más antiguo al más reciente
            var youngestReached = false
            var previousFOCWasPaid = true
            for(let i = 0; i < myPeriods.length; i++){
                var period = myPeriods[i]
        
                if(period === youngestPayablePeriod)
                    youngestReached = true
        
                // Iteramos hasta que encontremos el periodo pagable más antiguo y empezamos desde ahí
                if(!youngestReached)
                    continue
        
                if(cashierIntents[period] === undefined){
                    if(period === youngestPayablePeriod){ // El primer periodo pagable no está entre los productos
                        error = 'El primer periodo que debería ser facturado es ' + youngestPayablePeriod
                        break
                    }
                    else{                              
                        continue // El periodo no está entre los productos
                    }
                }
        
                if(previousFOCWasPaid === false){
                    // El estudiante NO pagó FOC el periodo anterior
                        if(cashierIntents[period].foc === 1){ // Se está intentando cobrar un FOC de un periodo adelantado sin pagar el anterior
                        error = 'Se intentó cobrar el FOC del periodo ' + period + ' sin haber cobrado los FOCs anteriores'
                        break
                    }
                }
        
                if(debtData.data[period].foc === 0){
                    // El estudiante ya pagó el FOC del periodo
                    if(cashierIntents[period].foc === 1){ // Se está intentando cobrar un FOC cuando el estudiante ya lo pagó
                        error = 'El estudiante ya pagó FOC en el periodo ' + period
                        break
                    }
                }
        
                if(debtData.data[period].foc > 0 && cashierIntents[period].foc === 0){
                    // El estudiante debe FOC y no se le va a cobrar en esta transacción, por lo tanto, no ha pagado el FOC de este periodo
                    previousFOCWasPaid = false
                }
        
                var previousMonthWasFullPaid = true
                var manteinMonthSucession = true
                for(let month in accountStates.data[period]){
                    var currentAccountState = accountStates.data[period][month]
                    var msgTemplate = 'el mes ' + month + ' del ' + period
        
                    if(cashierIntents[period].months[month] === undefined){ // El mes no se encuentra entre los conceptos
                        var monthNumber = GetMonthNumberByName(month)
                        if(parseInt(monthNumber) === parseInt(youngestPayableMonth[period])){ // Es el primer mes pagable y no hay ninguna intención de cobrarlo
                            error = 'El primer mes pagable es ' + msgTemplate + '. Pero ese mes no se encuentra entre los conceptos'
                            break
                        }
                        previousMonthWasFullPaid = false
                        manteinMonthSucession = false
                        continue
                    }
                    else{
                        if(manteinMonthSucession === false){ // Se está intentando pagar un mes por adelantado
                            error = 'Se está intentando cobrar ' + msgTemplate + ' por adelantado sin haber pagado los meses anteriores'
                        }
                    }
                    
                    var currentIntent = cashierIntents[period].months[month]
        
                    if(previousMonthWasFullPaid === false){
                        // El mes anterior no ha sido pagado por completo con una Mensualidad o Saldo Mensualidad
                        if(currentIntent.paid === 1 || currentIntent.debt === 1 || currentIntent.partial === 1 || currentIntent.remaining === 1){
                            // Se intentó cobrar una Mensualidad, Saldo Mensualidad, Diferencia Mensualidad, Abono Mensualidad
                            // Sin que los meses anteriores fuesen pagados por completo
                            error = 'Se intentó cobrar un concepto de mensualidad para ' + msgTemplate + ' sin haber pagado los meses anteriores'
                            break
                        }
                    }
        
                    if(currentAccountState.paid === 1){
                        if(currentIntent.paid === 1 || currentIntent.debt === 1 || currentIntent.partial === 1 || currentIntent.remaining === 1){
                            // Se intentó cobrarle una mensualidad, diferencia mensualidad o abono mensualidad a un mes que ya está pagado
                            error = 'El estudiante ya está solvente en ' + msgTemplate
                            break
                        }
                    }
        
                    if(currentAccountState.paid === 0 && currentAccountState.debt === 1){
                        // El estudiante NO ha pagado el mes y está moroso
                        if((currentIntent.paid === 1 || currentIntent.remaining === 1) && currentIntent.debt === 0){ // Intentando pagar el mes sin haber pagado la mora
                            error = 'El estudiante debe pagar la mora antes de poder pagar ' + msgTemplate
                            break
                        }
        
                        if(currentIntent.partial === 1 && currentIntent.debt === 0){ // Intentando abonar sin haber pagado la mora
                            error = 'El estudiante debe pagar la mora antes de poder abonar ' + msgTemplate
                            break
                        }                           
                    }
        
                    if(currentAccountState.paid === 0 && currentAccountState.debt === 0){
                        // El estudiante NO ha pagado el mes y NO está moroso
                        if(currentIntent.debt === 1){ // Intentando cobrarle la mora sin estar moroso
                            error = 'No puedes cobrarle la mora a un estudiante que no está moroso en ' + msgTemplate
                            break
                        }
                    }
        
                    if(currentAccountState.partial === 1){
                        // El estudiante ha abonado anteriormente al mes
                        if(currentIntent.paid === 1){ // Intentando cobrarle una Mensualidad cuando en realidad debería ser un Saldo Mensualidad
                            error = 'El estudiante ha abonado ' + msgTemplate + '. Por lo tanto se debe utilizar el concepto "Saldo Mensualidad" y no "Mensualidad"'
                            break
                        }
                    }
        
                    if(currentAccountState.partial === 0){
                        // El estudiante NO ha abonado anteriormente al mes
                        if(currentIntent.remaining === 1){ // Intentando cobrarle un Saldo Mensualidad cuando el estudiante no ha abonado nada
                            error = 'El estudiante ha abonado ' + msgTemplate + '. Por lo tanto se debe utilizar el concepto "Saldo Mensualidad" y no "Mensualidad"'
                            break
                        }
                    }
        
                    // Si llega hasta acá es que todas las validaciones de conceptos están bien
                    if(currentIntent.paid === 0 && currentIntent.remaining === 0){
                        // No se terminó de pagar el mes, por lo tanto, no se pueden aceptar pagos de meses futuros
                        previousMonthWasFullPaid = false
                    }
                }
        
                if(error !== '')
                    break
            }
        }
        */
    }
}


if($error === '' && isset($_POST['igtf-price'])){
    $to_add = [
        'method' => $target_payment_method['id'],
        'coin' => $target_coin['history_id'],
        'salepoint' => $target_sale_point['id'] ?? 'NULL',
        'bank' => $target_bank['id'] ?? 'NULL',
        'document_number' => ("'" . $target_document . "'") ?? 'NULL',
        'price' => $target_price,
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

if($error === '' && $_POST['known-income'] !== ''){
    $data = [
        'related_with' => 'invoice',
        'related_id' => $target_invoice['id']
    ];
    $remote_payments_model->SimpleUpdate('remote_payments', $data, $target_payment['id']);
}


if($error === ''){
    $action = "Creó la factura Nº $invoice_number, Numero de control: $control_number. Al cliente " . $target_account['names'] . ' ' . $target_account['surnames'] . ' de cédula ' . $target_account['cedula'];
    $invoice_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
    $message = 'Factura creada correctamente';
    $redirect = $base_url . '/views/detailers/invoice_details.php?id=' . $target_invoice['id'] . '&message=' . $message;
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
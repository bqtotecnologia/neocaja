<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/Validator.php';
include_once '../../utils/months_data.php';

$id = Validator::ValidateRecievedId();
$error = '';

if(is_string($id)){
    $error = $id;    
}

if($error === ''){
    include_once '../../models/invoice_model.php';
    $invoice_model = new InvoiceModel();

    $target_invoice = $invoice_model->GetInvoice($id);
    if($target_invoice === false){
        $error = 'Factura no encontrada';
    }
}

if($error !== ''){
    header("Location: $base_url/views/tables/search_invoices_of_today.php?error=$error");
    exit;
}

include_once '../../models/account_model.php';
include_once '../../models/siacad_model.php';
include_once '../../models/coin_model.php';
include_once '../../models/selfdata_model.php';

$account_model = new AccountModel();
$siacad = new SiacadModel();
$coin_model = new CoinModel();
$self_data_model = new SelfDataModel();

function MyDecode($str){
    if(is_numeric($str)){
        if(strpos($str, '.') === false)
            $display = $str . '.00';
        else
            $display = round(floatval($str), 2);

        if(strpos($display, '.') === false)
            $display = $display . '.00';

        $splits = explode('.', $display);
        $intPart = $splits[0];
        $intPart = mb_str_split($intPart);
        $decimalPart = $splits[1];
        $finalResult = '';
        $digitCount = 0;
        for ($i=(count($intPart) - 1); $i >= 0 ; $i--) { 
            $currentChar = $intPart[$i];

            if($digitCount === 3){
                $currentChar .= '.';
                $digitCount = 0;
            }
            else{
                $digitCount++;
            }

            $finalResult = $currentChar . $finalResult;
        }
        $finalResult .= ',' . $decimalPart;
    }
    else
        $finalResult = $str;

    return iconv('UTF-8', 'windows-1252', $finalResult);
}

$self_data = $self_data_model->GetSelfData();
$target_period = $siacad->GetPeriodoById($target_invoice['period']);
$target_account = $account_model->GetAccount($target_invoice['account_id']);
$payment_methods = $invoice_model->GetPaymentMethodsOfInvoice($id);
$concepts = $invoice_model->GetConceptsOfInvoice($id);
$coinValues = $coin_model->GetCoinValuesOfDate($target_invoice['rate_date']);

$hasCompany = $target_account['company_id'] !== null;

$upper_margin = 45;
include_once '../../vendors/fpdf/fpdf.php';
$pdf = new FPDF();

$pdf->AddPage();
$pdf->SetFont('Times', '', 9);

// Invoice header
$pdf->SetXY(8, $upper_margin);
$pdf->Cell(22, 4, 'Lugar', 1, 0, 'C');
$pdf->Cell(20, 4, 'Fecha', 1, 0, 'C');
$pdf->SetXY(8, $upper_margin + 4);
$pdf->Cell(22, 4, $self_data['city'], 1, 0, 'C');
$pdf->Cell(20, 4, date('d/m/Y'), 1, 0, 'C');
$pdf->Cell(83, 4, MyDecode($self_data['fullname']), 0, 0, 'C');
$pdf->SetXY(150, $upper_margin);
$pdf->Cell(22, 4, '"Contribuyente Formal"', 0, 0, 'C');
$pdf->SetXY(150, $upper_margin + 4);
$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(22, 4, MyDecode('FACTURA Nº: ' . $target_invoice['invoice_number']), 0, 0, 'C');

// Name and Cedula/Rif
$pdf->SetXY(8, $upper_margin + 9);
$pdf->Cell(142, 8, '', 1, 0, 'T');
$pdf->SetXY(8, $upper_margin + 9);
$pdf->Cell(142, 4, MyDecode('Nombre o razón social:'), 0, 0, 'L');
$pdf->Cell(52, 4, 'C.I/RIF:', 1, 0, 'C');
$pdf->SetXY(150, $upper_margin + 13);
$pdf->SetFont('Times', '', 9);

if($hasCompany){
    $pdf->Cell(52, 4, $target_account['rif_letter'] . '-' . $target_account['rif_number'], 1, 0, 'C');
    $pdf->SetXY(8, $upper_margin + 12);
    $pdf->Cell(135, 6, MyDecode($target_account['company']), 0, 0, 'L');
}
else{
    $pdf->Cell(52, 4, $target_account['cedula'], 1, 0, 'C');
    $pdf->SetXY(8, $upper_margin + 12);
    $pdf->Cell(135, 6, MyDecode($target_account['surnames'] . ' ' . $target_account['names']), 0, 0, 'L');
}

// Address, phone and contact people fields
$pdf->SetXY(8, $upper_margin + 17);
$pdf->Cell(142, 12, '', 1, 0);
$pdf->SetXY(8, $upper_margin + 17);
$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(142, 4, 'Domicilio Fiscal', 0, 0, 'L');
$pdf->Cell(26, 5, MyDecode('Teléfono:'), 1, 0, 'C');
$pdf->SetFont('Times', '', 9);

if($hasCompany)
    $pdf->Cell(26, 5, '', 1, 0, 'C');
else
    $pdf->Cell(26, 5, $target_account['phone'], 1, 0, 'C');

$pdf->SetFont('Times', 'B', 9);
$pdf->SetXY(150, $upper_margin + 22);
$pdf->Cell(26, 7, MyDecode('Persona contacto:'), 1, 0, 'C');
$pdf->SetFont('Times', '', 9);
$pdf->Cell(26, 7, '', 1, 0, 'C');
$pdf->SetXY(8, $upper_margin + 20);

if($hasCompany)
    $pdf->Cell(142, 4, MyDecode($target_company['address']), 0, 0, 'L');
else
    $pdf->Cell(142, 4, MyDecode($target_account['address']), 0, 0, 'L');

// Products and payment methods header
$pdf->SetFont('Times', 'B', 9);
$pdf->SetXY(8, $upper_margin + 29);
$pdf->Cell(142, 4, MyDecode('Concepto o descripción'), 1, 0, 'L');
$pdf->Cell(10, 4, 'Cant.', 1, 0, 'L');
$pdf->Cell(24, 4, 'Precio Unid Bs.', 1, 0, 'C');
$pdf->Cell(18, 4, 'Total Bs.', 1, 0, 'C');

// Products and payment methods empty box
$pdf->SetXY(8, $upper_margin + 33);
$pdf->Cell(142, 46, '', 1, 0, 'C');
$pdf->Cell(10, 46, '', 1, 0, 'C');
$pdf->Cell(24, 46, '', 1, 0, 'C');
$pdf->Cell(18, 46, '', 1, 0, 'C');

// Observation box
$pdf->SetXY(8, $upper_margin + 79);
$pdf->Cell(194, 6, MyDecode('Observación:'), 1, 0, 'L');
$pdf->SetFont('Times', '', 9);
$pdf->SetXY(15, $upper_margin + 79);
$pdf->Cell(194, 6, MyDecode($target_invoice['observation']), 0, 0, 'L');

// Listing products
$baseY = 91;
$rowPosition = 1;
$productsTotal = 0;

$ordered_products = [];

$periods = $siacad->GetperiodsAsDict(false);

for($i = 0; $i < count($concepts); $i++){
    $currentConcept = $concepts[$i];
    
    if(!isset($ordered_products[$currentConcept['period']])){
        $ordered_products[$currentConcept['period']] = [];
    }

    $to_add = [
        'head' => $currentConcept['product'],
        'body' => ($currentConcept['month'] === null ? '' : $month_translate[$currentConcept['month']] . ' '),
        'tail' => $periods[$currentConcept['period']]['nombreperiodo'],
        'month' => $currentConcept['month'],
        'quantity' => 1,
        'unit_price' => 0,
        'total_price' => 0
    ];

    $productUnitPrice = $currentConcept['price'] * $coinValues['Dólar'];
    $productQuantity = 1;

    if(strpos($currentConcept['product'], 'Mensualidad') !== false){
        while(isset($concepts[$i + 1])){
            if($concepts[$i + 1]['product'] === $currentConcept['product']){
                $to_add['body'] .= $month_translate[$concepts[$i + 1]['month']] . ' ';
                $i++;
                $productQuantity++;
            }
            else
                break;
        }
    }

    $productTotal = $productUnitPrice * $productQuantity;
    $productsTotal += $productTotal;

    $to_add['quantity'] = $productQuantity;
    $to_add['unit_price'] = $productUnitPrice;
    $to_add['total_price'] = $productTotal;

    array_push($ordered_products[$currentConcept['period']], $to_add);
}

foreach($ordered_products as $period => $products){
    foreach($products as $product){
        $finalName = $product['head'] . ': ' . $product['body'] . $product['tail'];
    
        $pdf->SetXY(9, $upper_margin + 32 + $rowPosition);
        $pdf->Cell(142, 4, $finalName, 0, 0, 'L');
        $pdf->Cell(8, 4, $product['quantity'], 0, 0, 'C');
        $pdf->Cell(24, 4, MyDecode($product['unit_price']), 0, 0, 'R');
        $pdf->Cell(18, 4, MyDecode($product['total_price']), 0, 0, 'R');
        $rowPosition += 3;
    }
}

// Payment methods header
$rowPosition += 10;
$pdf->SetFont('Times', 'B', 9);
$pdf->SetXY(10, $upper_margin + 25 + $rowPosition);
$pdf->Cell(138, 4, MyDecode('Métodos de pago'), 'T', 0, 'L');

$rowPosition += 5;

$payment_col_widths = [
    'type' => 25, 
    'coin' => 14, 
    'price' => 17, 
    'rate' => 14, 
    'bank' => 34,
    'document_number' => 20,
    'bs_price' => 16
];

$pdf->SetXY(10, $upper_margin + 23 + $rowPosition);
$pdf->Cell($payment_col_widths['type'], 4, 'Tipo', 0, 0, 'L');
$pdf->Cell($payment_col_widths['coin'], 4, 'Moneda', 0, 0, 'L');
$pdf->Cell($payment_col_widths['price'], 4, 'Monto', 0, 0, 'L');
$pdf->Cell($payment_col_widths['rate'], 4, 'Tasa', 0, 0, 'L');
$pdf->Cell($payment_col_widths['bank'], 4, 'Banco', 0, 0, 'L');
$pdf->Cell($payment_col_widths['document_number'], 4, 'No. Doc.', 0, 0, 'L');
$pdf->Cell($payment_col_widths['bs_price'], 4, 'Monto Bs.', 0, 0, 'L');

$igtf = null;
$rowPosition += 6;
$pdf->SetFont('Times', '', 9);
// Listing the payment methods
foreach($payment_methods as $payment_method){
    if(intval($payment_method['igtf']) === 1){
        $igtf = $payment_method;
        continue;
    }
    $pdf->SetXY(10, $upper_margin + 21 + $rowPosition);
    $pdf->Cell($payment_col_widths['type'], 4, MyDecode($payment_method['payment_method']), 0, 0, 'L');
    $pdf->Cell($payment_col_widths['coin'], 4, MyDecode($payment_method['coin']), 0, 0, 'L');
    $pdf->Cell($payment_col_widths['price'], 4, MyDecode($payment_method['price']), 0, 0, 'L');
    $pdf->Cell($payment_col_widths['rate'], 4, MyDecode($coinValues[$payment_method['coin']]), 0, 0, 'L');
    $pdf->Cell($payment_col_widths['bank'], 4, MyDecode($payment_method['bank']), 0, 0, 'L');
    $pdf->Cell($payment_col_widths['document_number'], 4, $payment_method['document_number'], 0, 0, 'L');
    $pdf->Cell($payment_col_widths['bs_price'], 4, MyDecode($payment_method['price'] * $coinValues[$payment_method['coin']]), 0, 0, 'R');
    $rowPosition += 3;
}

//Adding IGTF
$igtfTotal = 0;
if($igtf !== null){
    $pdf->SetFont('Times', 'B', 9);
    $pdf->SetXY(10, $upper_margin + 34 + $rowPosition);
    $pdf->Cell(20, 4, MyDecode('IGTF'), 0, 0, 'L');
    $pdf->SetFont('Times', '', 9);
    $rowPosition += 4;
    $pdf->SetXY(10, $upper_margin + 34 + $rowPosition);
    $pdf->Cell(25, 4, MyDecode($igtf['payment_method']), 0, 0, 'L');
    $pdf->Cell(18, 4, MyDecode($igtf['coin']), 0, 0, 'L');
    $pdf->Cell(16, 4, MyDecode($igtf['price']), 0, 0, 'L');
    $pdf->Cell(16, 4, MyDecode($coinValues[$igtf['coin']]), 0, 0, 'L');
    $pdf->Cell(32, 4, MyDecode($igtf['bank']), 0, 0, 'L');
    $pdf->Cell(15, 4, $igtf['document_number'], 0, 0, 'L');
    $igtfTotal = $igtf['price'] * $coinValues[$igtf['coin']];
    $pdf->Cell(16, 4, MyDecode($igtfTotal), 0, 0, 'R');
    $rowPosition += 3;
}

// Listing other concepts
$iva = 0;
$other_concepts = [
    'Total a pagar' => $igtfTotal + $iva + $productsTotal,
    'IGTF' => $igtfTotal,
    'Total Factura' => $productsTotal + $iva,
    'IVA 16%' => $iva,
    'Base Imponible' => $productsTotal,
    'Sub-total' => $productsTotal,    
];

$box_limit = $upper_margin + 75;
$other_concept_position = 0;
foreach($other_concepts as $key => $value){
    $pdf->SetFont('Times', 'B', 9);
    $pdf->SetXY(160, $box_limit - $other_concept_position);
    $pdf->Cell(24, 4, MyDecode($key), 0, 0, 'R');
    $pdf->SetFont('Times', '', 9);
    $pdf->Cell(18, 4, MyDecode($value), 0, 0, 'R');
    $other_concept_position += 3;
}

$pdf->Output();     

exit;

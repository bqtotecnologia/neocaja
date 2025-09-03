<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';
include_once '../../utils/Validator.php';
include_once '../../utils/months_data.php';

$id = Validator::ValidateRecievedId();
$error = '';

if(is_string($id)){
    $error = $id;    
}

include_once '../../models/invoice_model.php';
include_once '../../models/account_model.php';
include_once '../../models/siacad_model.php';
include_once '../../models/coin_model.php';
include_once '../../models/selfdata_model.php';

$invoice_model = new InvoiceModel();
$account_model = new AccountModel();
$siacad = new SiacadModel();
$coin_model = new CoinModel();
$self_data_model = new SelfDataModel();

$target_invoice = $invoice_model->GetInvoice($id);
if($target_invoice === false){
    $error = 'Factura no encontrada';
}

if($error !== ''){
    header('Location: ' . $base_url . '/views/tables/search_invoices_of_today.php?error='. $error);
    exit;
}

function MyDecode($str){
    return iconv('UTF-8', 'windows-1252', $str);
}

$self_data = $self_data_model->GetSelfData();
$target_period = $siacad->GetPeriodoById($target_invoice['period']);
$target_account = $account_model->GetAccount($target_invoice['account_id']);
$payment_methods = $invoice_model->GetPaymentMethodsOfInvoice($id);
$concepts = $invoice_model->GetConceptsOfInvoice($id);
$coinValues = $coin_model->GetCoinValuesOfDate($target_invoice['rate_date']);

$upper_margin = 45;
include_once '../../vendors/fpdf/fpdf.php';
$pdf = new FPDF();

$pdf->AddPage();
$pdf->SetFont('Times', '', 11);

// Invoice header
$pdf->SetXY(8, $upper_margin);
$pdf->Cell(25, 6, 'Lugar', 1, 0, 'C');
$pdf->Cell(25, 6, 'Fecha', 1, 0, 'C');
$pdf->SetXY(8, $upper_margin + 6);
$pdf->Cell(25, 6, $self_data['city'], 1, 0, 'C');
$pdf->Cell(25, 6, date('d/m/Y'), 1, 0, 'C');
$pdf->Cell(105, 6, MyDecode($self_data['fullname']), 0, 0, 'C');
$pdf->SetXY(180, $upper_margin);
$pdf->Cell(25, 6, '"Contribuyente Formal"', 0, 0, 'C');
$pdf->SetXY(180, $upper_margin + 6);
$pdf->SetFont('Times', 'B', 11);
$pdf->Cell(25, 6, MyDecode('FACTURA Nº: ' . $target_invoice['invoice_number']), 0, 0, 'C');

// Name and Cedula/Rif
$pdf->SetXY(8, $upper_margin + 14);
$pdf->Cell(185, 12, '', 1, 0, 'T');
$pdf->SetXY(8, $upper_margin + 14);
$pdf->Cell(185, 6, MyDecode('Nombre o razón social:'), 0, 0, 'L');
$pdf->Cell(94, 6, 'C.I/RIF:', 1, 0, 'C');
$pdf->SetXY(193, $upper_margin + 20);
$pdf->SetFont('Times', '', 11);
$pdf->Cell(94, 6, $target_account['cedula'], 1, 0, 'C');
$pdf->SetXY(8, $upper_margin + 20);
$pdf->Cell(185, 6, MyDecode($target_account['names'] . ' ' . $target_account['surnames']), 0, 0, 'L');

// Address, phone and contact people fields
$pdf->SetXY(8, $upper_margin + 26);
$pdf->Cell(185, 16, '', 1, 0);
$pdf->SetXY(8, $upper_margin + 26);
$pdf->SetFont('Times', 'B', 11);
$pdf->Cell(185, 6, 'Domicilio Fiscal', 0, 0, 'L');
$pdf->Cell(47, 8, MyDecode('Teléfono:'), 1, 0, 'C');
$pdf->SetFont('Times', '', 11);
$pdf->Cell(47, 8, $target_account['phone'], 1, 0, 'C');
$pdf->SetFont('Times', 'B', 11);
$pdf->SetXY(193, $upper_margin + 34);
$pdf->Cell(47, 8, MyDecode('Persona contacto:'), 1, 0, 'C');
$pdf->SetFont('Times', '', 11);
$pdf->Cell(47, 8, '', 1, 0, 'C');
$pdf->SetXY(8, $upper_margin + 32);
$pdf->Cell(185, 5, $target_account['address'], 0, 0, 'L');

// Products and payment methods header
$pdf->SetFont('Times', 'B', 11);
$pdf->SetXY(8, $upper_margin + 42);
$pdf->Cell(170, 6, MyDecode('Concepto o descripción'), 1, 0, 'L');
$pdf->Cell(15, 6, 'Cant.', 1, 0, 'L');
$pdf->Cell(47, 6, 'Precio Unid Bs.', 1, 0, 'C');
$pdf->Cell(47, 6, 'Total Bs.', 1, 0, 'C');

// Products and payment methods empty box
$pdf->SetXY(8, $upper_margin + 48);
$pdf->Cell(170, 85, '', 1, 0, 'C');
$pdf->Cell(15, 85, '', 1, 0, 'C');
$pdf->Cell(47, 85, '', 1, 0, 'C');
$pdf->Cell(47, 85, '', 1, 0, 'C');

// Observation box
$pdf->SetXY(8, $upper_margin + 133);
$pdf->Cell(279, 6, MyDecode('Observación:'), 1, 0, 'L');
$pdf->SetFont('Times', '', 11);
$pdf->SetXY(15, $upper_margin + 133);
$pdf->Cell(279, 6, MyDecode($target_invoice['observation']), 0, 0, 'L');

// Listing products
$baseY = 91;
$rowPosition = 1;
$productsTotal = 0;
$igtf = 0;
foreach($concepts as $concept){
    $productPrice = $coinValues['Dólar'] * $concept['price'];
    if($concept['product'] === 'IGTF'){
        $productPrice = $concept['price'];
        $igtf = $concept['price'];
    }

    $pdf->SetXY(9, $upper_margin + 48 + $rowPosition);
    $pdf->Cell(169, 6, $concept['product'], 0, 0, 'L');
    $pdf->Cell(15, 6, '1', 0, 0, 'C');
    $productsTotal += $productPrice;
    $pdf->Cell(47, 6, $productPrice, 0, 0, 'R');
    $pdf->Cell(47, 6, $productPrice, 0, 0, 'R');
    $rowPosition += 6;
}

// Payment methods header
$rowPosition += 10;
$pdf->SetFont('Times', 'B', 11);
$pdf->SetXY(10, $upper_margin + 48 + $rowPosition);
$pdf->Cell(166, 6, MyDecode('Métodos de pago'), 'T', 0, 'L');

$rowPosition += 6;
$pdf->SetXY(10, $upper_margin + 48 + $rowPosition);
$pdf->Cell(28, 6, 'Tipo', 0, 0, 'L');
$pdf->Cell(25, 6, 'Moneda', 0, 0, 'L');
$pdf->Cell(18, 6, 'Monto', 0, 0, 'L');
$pdf->Cell(18, 6, 'Tasa', 0, 0, 'L');
$pdf->Cell(38, 6, 'Banco', 0, 0, 'L');
$pdf->Cell(18, 6, 'No. Doc.', 0, 0, 'L');
$pdf->Cell(20, 6, 'Monto Bs.', 0, 0, 'L');
$rowPosition += 6;
$pdf->SetFont('Times', '', 11);
// Listing the payment methods
foreach($payment_methods as $payment_method){
    $pdf->SetXY(10, $upper_margin + 48 + $rowPosition);
    $pdf->Cell(28, 6, MyDecode($payment_method['payment_method']), 0, 0, 'L');
    $pdf->Cell(25, 6, MyDecode($payment_method['coin']), 0, 0, 'L');
    $pdf->Cell(18, 6, MyDecode($payment_method['price']), 0, 0, 'L');
    $pdf->Cell(18, 6, MyDecode($coinValues[$payment_method['coin']]), 0, 0, 'L');
    $pdf->Cell(38, 6, MyDecode($payment_method['bank']), 0, 0, 'L');
    $pdf->Cell(18, 6, MyDecode('10NO188'), 0, 0, 'L');
    $pdf->Cell(20, 6, MyDecode($payment_method['price'] * $coinValues[$payment_method['coin']]), 0, 0, 'L');
    $rowPosition += 6;
}

// Listing other concepts
$iva = 0;
$other_concepts = [
    'Total a pagar' => $igtf + $iva + $productsTotal,
    'IGTF' => $igtf,
    'Total Factura' => $productsTotal + $iva,
    'IVA 16%' => $iva,
    'Base Imopnible' => $productsTotal,
    'Sub-total' => $productsTotal,    
];

$box_limit = $upper_margin + 127;
$other_concept_position = 0;
foreach($other_concepts as $key => $value){
    $pdf->SetFont('Times', 'B', 11);
    $pdf->SetXY(193, $box_limit - $other_concept_position);
    $pdf->Cell(47, 6, MyDecode($key . ':'), 0, 0, 'R');
    $pdf->SetFont('Times', '', 11);
    $pdf->Cell(47, 6, $value, 0, 0, 'R');
    $other_concept_position += 6;
}







$pdf->Output();     

exit;

foreach($recibos as $recibo){
    $weekly_corresponded_hours = $recibo['horas_semanales_presenciales'] + $recibo['horas_semanales_virtuales'];
    $corresponded_hours = $weekly_corresponded_hours * 4;

    $conceptos = $recibo_model->GetConceptosOfRecibo($recibo['recibo_id']);
    $fist_name = explode(' ', $recibo['nombres'])[0];
    $fist_lastname = explode(' ', $recibo['apellidos'])[0];
    $fullname = $fist_name . ' ' . $fist_lastname;

    $pdf->AddPage('O');
    $pdf->Image('../images/iujo-transparent.png', 10, 10, 50);
    
    $pdf->SetFont('Times', 'B', 16);
    
    $pdf->SetXY(10, 28); $pdf->Cell(0, 0, $title, 0, 1, 'C');

    $pdf->SetXY(10, 50); $pdf->Cell(0, 0, 'Beneficiario:', 0, 1);

    $pdf->SetXY(110, 50); $pdf->Cell(0, 0, 'Desde:', 0, 1);
    $pdf->SetXY(110, 57); $pdf->Cell(0, 0, 'Hasta:', 0, 1);

    $pdf->SetXY(10, 71); $pdf->Cell(0, 0, 'Cedula:', 0, 1);
    $pdf->SetXY(10, 78); $pdf->Cell(0, 0, 'Cargo:', 0, 1);
    $pdf->SetXY(10, 85); $pdf->Cell(0, 0, 'Fecha de ingreso:', 0, 1);
    
    $pdf->SetXY(110, 71); $pdf->Cell(0, 0, Decode('Fecha de emisión:'), 200, 200);
    $pdf->SetXY(110, 78); $pdf->Cell(0, 0, 'Horas mensuales:', 200, 200);

    
    if($recibo['tipo'] === 'Bono alimentario'){
        $pdf->SetXY(110, 85); $pdf->Cell(0, 0, $recibo['is_teacher'] === '1' ? 'Valor hora:' : 'Valor dia:', 200, 200);
        $daily_bono = round(($globals['Bono alimentario'] / 30), 2);
        if($recibo['is_teacher']){
            $value = $daily_bono / 10;
        }
        else{
            $value = $daily_bono;
        }
        $pdf->SetFont('Times','', 16);
        $pdf->SetXY(150, 85); $pdf->Cell(0, 0, $value, 0, 1);
    }
    

    $pdf->SetFont('Times','', 16);
    $pdf->SetXY(44, 50); $pdf->Cell(0, 0, Decode($fullname), 0, 1);

    $pdf->SetXY(130, 50); $pdf->Cell(0, 0, date('d/m/Y', strtotime($generacion['fecha_inicio'])), 0, 1);
    $pdf->SetXY(130, 57); $pdf->Cell(0, 0, date('d/m/Y', strtotime($generacion['fecha_fin'])), 0, 1);

    $pdf->SetXY(31, 71); $pdf->Cell(0, 0, $recibo['cedula'], 10, 10);
    $pdf->SetXY(32, 78); $pdf->Cell(0, 0, Decode($recibo['cargo']), 0, 1);
    $pdf->SetXY(56, 85); $pdf->Cell(0, 0, date('d/m/Y', strtotime($recibo['fecha_ingreso'])), 0, 1);
    
    $pdf->SetXY(155, 71); $pdf->Cell(0, 0, date('d/m/Y'), 10, 10);
    $pdf->SetXY(155, 78); $pdf->Cell(0, 0, $corresponded_hours, 10, 10);

    
    $asignaciones = $conceptos['asignaciones'];
    $deducciones = $conceptos['deducciones'];
    
    $total_asignaciones = 0;
    $total_deducciones = 0;
    $asignaciones_count = count($asignaciones);
    $deducciones_count = count($deducciones);

    $bigger_row_number = abs($deducciones_count - $asignaciones_count);
    
    $widths = array(60, 25);
    $pdf->SetXY(10, 105);

    // Mostrando asignaciones
    $pdf->SetFont('Times', 'B', 14);
    $pdf->Cell(85, 7, 'Asignaciones', 1, 0, 'C');
    $pdf->Ln();
    $pdf->SetFont('Times', '', 12);
    
    foreach($asignaciones as $row)
    {
        $total_asignaciones += $row['monto'];
        $pdf->Cell($widths[0], 6, $row['concepto'], 1, 0, 'C');
        $pdf->Cell($widths[1], 6, $row['monto'], 1, 0, 'C');
        $pdf->Ln();
    }
    // Total de las asignaciones
    $pdf->SetFont('Times', 'B', 13);
    $pdf->Cell($widths[0], 6, 'Total', 1, 0, 'R');
    $pdf->Cell($widths[1], 6, $total_asignaciones, 1, 0, 'C');
    $pdf->Ln();
    
    $pdf->Cell(array_sum($widths),0,'','T');
    
    
    // Mostrando deduciones
    $pdf->SetXY(105, 105);
    $pdf->SetFont('Times', 'B', 14);
    $pdf->Cell(85, 7, 'Deducciones', 1, 0, 'C');
    $pdf->Ln();
    $pdf->SetFont('Times', '', 12);
    
    foreach($deducciones as $row)
    {
        $pdf->SetX(105);
        $total_deducciones += $row['monto'];
        $pdf->Cell($widths[0],6,$row['concepto'], 1, 0, 'C');
        $pdf->Cell($widths[1],6,$row['monto'], 1, 0, 'C');
        $pdf->Ln();
    }
    // Total de las deducciones
    $pdf->SetX(105);
    $pdf->SetFont('Times', 'B', 13);
    $pdf->Cell($widths[0], 6, 'Total', 1, 0, 'R');
    $pdf->Cell($widths[1], 6, $total_deducciones, 1, 0, 'C');
    $pdf->Ln();
    
    $pdf->Cell(array_sum($widths),0,'');
    
    $to_pay = $total_asignaciones - $total_deducciones;
    $pdf->SetFont('Times', 'B', 16);
    $pdf->Cell(120, ($bigger_row_number + 3) * 9, 'NETO RECIBIDO Bs.         ' . $to_pay);   
}

$message = "Exportó los recibos de " . $generacion['fecha_creacion'] . " como pdf";
$recibo_model->CreateBinnacle($_SESSION['hor_user_id'], $message);

$pdf->Output();        

<?php
$admitted_user_types = ['Cajero', 'Super', 'Administrador'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';

$error = '';
$target_invoice = false;

if(empty($_POST)){
    $error = 'POST vacío';
}

$edit = isset($_POST['id']);
$form = false;
if($error === '' && $edit){
    $id = Validator::ValidateRecievedId('id', 'POST');
    if(is_string($id))
        $error = $id;
}

if($error === ''){
    include_once '../models/invoice_model.php';
    $invoice_model = new InvoiceModel();

    $target_invoice = $invoice_model->GetInvoice($id);
    if($target_invoice === false)
        $error = 'Factura no encontrada';
}

if($error === ''){
    $reason = $_POST['reason'];
    if(Validator::HasSuspiciousCharacters($reason))
        $error = 'El motivo o la descripción de la nota de crédito contiene caracteres sospechosos';
}

if($error === ''){
    if(strlen($reason) > 255)
        $error = 'El motivo o descripción supera el límite de caracteres';
}

if($error === ''){
    if($target_invoice['invoice_state'] !== 'Activa')
        $error = 'No se pueden reversar facturas que no estén activas';
}

if($error === ''){
    $data = [
        'invoice' => $target_invoice['id'],
        'reason' => $reason
    ];
    $annulled = $invoice_model->AnullInvoice($data);
    if($annulled === false)
        $error = 'Hubo un error al intentar anular la factura';
}

// Managing feedback message and binnacle
if($error === ''){
    $action = 'Anuló la factura de número ' . $target_invoice['invoice_number'] . ' por motivo: ' . $reason;
    $invoice_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){    
    $message = 'Factura anulada correctamente';
    header("Location: $base_url/views/detailers/invoice_details.php?message=$message&id=" . $target_invoice['id']);
}
else{
    if($target_invoice === false)
        header("Location: $base_url/views/tables/search_invoices_by_period.php?error=$error");
    else
        header("Location: $base_url/views/detailers/invoice_details.php?error=$error&id=" . $target_invoice['id']);
}

exit;
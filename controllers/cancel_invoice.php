<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';

include_once '../utils/base_url.php';
include_once '../utils/Validator.php';

$error = '';
$target_invoice = false;

if(empty($_GET)){
    $error = 'POST vacío';
}

if($error === ''){
    $id = Validator::ValidateRecievedId();
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
    $result = $invoice_model->AnullInvoice($id);
    if($result === false)
        $error = 'Hubo un error al intentar anular la factura';
}

if($error === ''){
    $action = 'Anuló la factura Nº ' . $target_invoice['invoice_number'];
    $invoice_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){
    $redirect = $base_url . '/views/panel.php?message=Factura anulada exitosamente';
}
else{
    if($target_invoice !== false)
        $redirect = $base_url . '/views/detailers/invoice_details.php?error=' . $error . '&id=' . $target_invoice['id'];
    else
        $redirect = $base_url . '/views/panel.php?error=' . $error;
}

header('Location: ' . $redirect);
exit;

<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';

include_once '../models/payment_method_model.php';
$payment_method_model = new PaymentMethodModel();

$error = '';
$target_payment_method = false;

$id = Validator::ValidateRecievedId('id', 'POST');
if(is_string($id))
    $error = $id;

if($error === ''){
    $target_payment_method = $payment_method_model->GetPaymentMethodType($id);
    if($target_payment_method === false)
        $error = 'Método de pago no encontrado';
}

if($error === ''){
    if($payment_method_model->PaymentMethodIsDeletable($id) === false){
        $error = 'No se puede borrar el método de pago porque aparece en una o más facturas';
    }
}

if($error === ''){
    $deleted = $payment_method_model->DeletePaymentMethod($id);
    if($deleted === false)
        $error = 'Hubo un error al intentar borrar el método de pago';
}

if($error === ''){
    $binnacle = "Borró el método de pago " . $target_payment_method['name'];
    $payment_method_model->CreateBinnacle($_SESSION['neocaja_id'], $binnacle);
    header("Location: ../views/tables/search_payment_method.php?message=Método de pago borrado exitosamente");
}
else{
    if($target_payment_method === false)
        header("Location: ../views/tables/search_payment_method.php?error=$error");
    else
        header("Location: ../views/forms/payment_method_form.php?error=$error&id=" . $target_payment_method['id']);
}
exit;
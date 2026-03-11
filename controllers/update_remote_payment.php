<?php

$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';

$remotePaymentFields = [
    [
        'name' => 'document',
        'type' => 'text',
        'max' => 20,
        'min' => 7,
        'required' => true,
        'suspicious' => true,
    ],
    [
        'name' => 'ref',
        'type' => 'text',
        'max' => 20,
        'min' => 6,
        'required' => true,
        'suspicious' => true,
    ],
    [
        'name' => 'price',
        'type' => 'decimal',
        'required' => true,
        'suspicious' => true,
    ],
    [
        'name' => 'date',
        'type' => 'date',
        'required' => true,
        'suspicious' => true,
    ],
];  

$error = '';

if(empty($_POST)){
    $error = 'POST vacío';
}

if($error === ''){
    $id = Validator::ValidateRecievedId('id', 'POST');
    if(is_string($id))
        $error = $id;
}

$target_payment = false;
if($error === ''){
    include_once '../models/remote_payments_model.php';
    $payments_model = new RemotePaymentsModel();

    $target_payment = $payments_model->GetAccountPayment($id);
    if($target_payment === false)
        $error = 'Pago no encontrado';
}

if($error === ''){
    $cleanData = Validator::ValidatePOSTFields($remotePaymentFields);
    if(is_string($cleanData))
        $error = $cleanData;
}

if($error === ''){
    $cleanData['date'] = $cleanData['date']->format('Y-m-d');   

    $updated = $payments_model->SimpleUpdate('remote_payments', $cleanData, $target_payment['id']);
    

    if($updated === false)
        $error = 'Hubo un error al intentar actualizar el pago';
}

if($error === ''){
    // Creando al bitácora
    $action = 'Actualizó el estado del pago remoto ' . $target_payment['id'] . ' perteneciente al cliente ' . $target_payment['fullname'];
    $action .= ' de cédula: ' . $target_payment['cedula'];
    $action .= ' al documento: ' . $cleanData['document'] . ' a la referencia: ' . $cleanData['ref'] . ' a la fecha: ' . $cleanData['date'] . ' al monto: ' . $cleanData['price'] . 
    $payments_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){    
    header("Location: $base_url/views/forms/update_remote_payment.php?message=Pago remoto actualizado correctamente&id=" . $target_payment['id']);
}
else{
    if($target_payment === false)
        header("Location: $base_url/views/panel.php?error=$error");
    else
        header("Location: $base_url/views/forms/update_remote_payment.php?error=$error&id=" . $target_payment['id']);
}

exit;

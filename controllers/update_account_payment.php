<?php

$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';

include_once '../utils/base_url.php';
include_once '../utils/Validator.php';

$error = '';

if(empty($_POST)){
    $error = 'POST vacío';
}

if($error === ''){
    $id = Validator::ValidateRecievedId('id', 'POST');
    if(is_string($id))
        $error = $id;
}

if($error === ''){
    if(!in_array($_POST['state'], ['Aprobado', 'Rechazado']))
        $error = 'Estado inválido';
}

if($error === ''){
    if($_POST['response'] === '')
        $error = 'La respuesta no puede estar vacía';
}

$target_payment = false;
if($error === ''){
    include_once '../models/account_payments_model.php';
    $payments_model = new AccountPaymentsModel();

    $target_payment = $payments_model->GetAccountPayment($id);
    if($target_payment === false)
        $error = 'Pago no encontrado';
}

if($error === '' && $_POST['unknown-income'] !== "0"){
    $income_id = Validator::ValidateRecievedId('unknown-income', 'POST');
    if(is_string($id))
        $error = $income_id;
}

if($error === '' && isset($income_id)){
    include_once '../models/unknown_incomes_model.php';
    $unknown_model = new UnknownIncomesModel();

    $target_income = $unknown_model->GetUnknownIncome($income_id);
    if($target_income === false)
        $error = 'El ingreso no identificado seleccionado no pudo ser encontrado';
}

if($error === ''){
    $data = [
        'state' => $_POST['state'],
        'response' => $_POST['response']
    ];
    
    $updated = $payments_model->ProcessPayment($id, $data);
    if($updated === false)
        $error = 'Hubo un error al intentar actualizar el estado del pago';
}

if($error === '' && isset($income_id)){
    if($_POST['state'] === 'Aprobado'){
        $data = ['account' => $target_payment['account_id']];
        $unknown_model->SimpleUpdate('unknown_incomes', $data, $target_income['id']);
    }
}

if($error === ''){
    // Creando al bitácora
    $action = 'Actualizó el estado del pago remoto ' . $target_payment['id'] . ' perteneciente al cliente ' . $target_payment['fullname'];
    $action .= ' cédula: ' . $target_payment['cedula']  . ' al estado: ' . $_POST['state'] . ' por motivo: ' . $_POST['response'];
    $payments_model->CreateBinnacle($_SESSION['neocaja_id'], $action);

    // Creando la notificación
    include_once '../models/notification_model.php';
    $notification = 'Tu pago remoto realizado el ' . date('d/m/Y', strtotime($target_payment['created_at']));
    $notification .= ' fue actualizado al estado "' . $_POST['state'] . '"' . '. Respuesta: ' . $_POST['response'];
    $data = [
        'message' => $notification,
        'cedula' => $target_payment['cedula']
    ];

    $notification_model->CreateNotification($data);    
}

if($error === ''){    
    header("Location: $base_url/views/detailers/payment_details.php?message=Pago remoto actualizado correctamente&id=" . $target_payment['id']);
}
else{
    if($target_payment === false)
        header("Location: $base_url/views/panel.php?error=$error");
    else
        header("Location: $base_url/views/detailers/payment_details.php?error=$error&id=" . $target_payment['id']);
}

exit;

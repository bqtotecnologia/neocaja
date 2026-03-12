<?php

$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';

$remotePaymentFields = [
    [
        'name' => 'state',
        'type' => 'text',
        'max' => 20,
        'min' => 5,
        'required' => true,
        'suspicious' => true,
    ],
    [
        'name' => 'response',
        'type' => 'text',
        'max' => 255,
        'min' => 5,
        'required' => true,
        'suspicious' => true,
    ],
    [
        'name' => 'selected_income',
        'type' => 'integer',
        'max' => 11,
        'min' => 0,
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
    if(!in_array($cleanData['state'], ['Conciliado', 'Rechazado']))
        $error = 'Estado inválido';
}

if($error === ''){
    if($cleanData['response'] === '')
        $error = 'La respuesta no puede estar vacía';
}

if($error === ''){
    if(intval($cleanData['selected_income']) !== 0){
        $income_id = Validator::ValidateRecievedId('selected_income', 'POST');
        if(is_string($id))
            $error = $income_id;
    }
}

if($error === ''){
    include_once '../models/unknown_incomes_model.php';
    $unknown_model = new UnknownIncomesModel();

    if(isset($income_id)){
        $target_income = $unknown_model->GetUnknownIncome($income_id);
        if($target_income === false)
            $error = 'El ingreso no identificado seleccionado no pudo ser encontrado';
    }
}

if($error === '' && isset($target_income)){
    if(intval($target_income['remote_payment']) !== intval($target_payment['id']) && $target_income['remote_payment'] !== null)
        $error = 'El ingreso no identificado seleccionado ya pertenece a un pago remoto';
}

if($error === ''){    
    $updated = $payments_model->ProcessPayment($id, $cleanData);
    if($updated === false)
        $error = 'Hubo un error al intentar actualizar el estado del pago';
}


if($error === ''){   
    if(isset($income_id)){
        // Se recibió un ingreso desconocido y se vincula con el pago remoto
        $data = ['remote_payment' => $target_payment['id']];
        $unknown_model->SimpleUpdate('unknown_incomes', $data, $target_income['id']);
    }
    else{
        // No se recibió un ingreso desconocido, de haber estado vinculado con alguno, se desvincula
        $unknown_model->UnlinkFromRemotePayment($target_payment['id']);
    }       
}

if($error === ''){
    // Creando al bitácora
    $action = 'Actualizó el estado del pago remoto ' . $target_payment['id'] . ' perteneciente al cliente ' . $target_payment['fullname'];
    $action .= ' cédula: ' . $target_payment['cedula']  . ' al estado: ' . $cleanData['state'] . ' por motivo: ' . $cleanData['response'];
    $payments_model->CreateBinnacle($_SESSION['neocaja_id'], $action);

    // Creando la notificación
    include_once '../models/notification_model.php';
    $notification = 'Tu pago remoto realizado el ' . date('d/m/Y', strtotime($target_payment['created_at']));
    $notification .= ' fue actualizado al estado "' . $cleanData['state'] . '"' . '. Respuesta: ' . $cleanData['response'];
    $data = [
        'message' => $notification,
        'cedula' => $target_payment['cedula']
    ];

    $notification_model->CreateNotification($data);    
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

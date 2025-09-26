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

if($error === ''){
    $data = [
        'state' => $_POST['state'],
        'response' => $_POST['response']
    ];
    
    $updated = $payments_model->ProcessPayment($id, $data);
    if($updated === false)
        $error = 'Hubo un error al intentar actualizar el estado del pago';
    
}

// binnacle

if($error === ''){    
    header("Location: $base_url/views/detailers/payment_details.php?message=$message&id=" . $target_payment['id']);
}
else{
    if($target_payment === false)
        header("Location: $base_url/views/panel.php?error=$error");
    else
        header("Location: $base_url/views/detailers/payment_details.php?error=$error&id=" . $target_payment['id']);
}

exit;

<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';

include_once '../utils/base_url.php';
include_once '../utils/Validator.php';

$error = '';
$target_mobile_payment = false;

if(empty($_POST)){
    $error = 'POST vacío';
}

$fields_config = [
    'phone' => [
        'min' => 11,
        'max' => 11,
        'required' => true,
        'type' => 'string',
        'suspicious' => true,
    ],
    'document_letter' => [
        'min' => 1,
        'max' => 1,
        'required' => true,
        'type' => 'string',
        'suspicious' => true,
    ],
    'document_number' => [
        'min' => 7,
        'max' => 45,
        'required' => true,
        'type' => 'string',
        'suspicious' => true,
    ],
    'bank' => [
        'min' => 5,
        'max' => 60,
        'required' => true,
        'type' => 'string',
        'suspicious' => true,
    ],
];

$result = Validator::ValidatePOSTFields($fields_config);
if(is_string($result))
    $error = $result;
else
    $cleanData = $result;

$edit = isset($_POST['id']);

if($error === ''){
    include_once '../models/mobile_payments_model.php';
    $mobile_payment_model = new MobilePaymentsModel();

    if($edit){
        $target_mobile_payment = $mobile_payment_model->GetMobilePayment($cleanData['id']);
        if($target_mobile_payment === false)
            $error = 'Pago móvil no encontrado';
    }
}


// Creating / updating the mobile_payment
if($error === ''){
    $cleanData['active'] = intval(isset($_POST['active']));
    if($edit){
        $updated = $mobile_payment_model->SimpleUpdate('mobile_payments', $cleanData, $cleanData['id']);
        
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar el pago móvil';
    }
    else{
        $created = $mobile_payment_model->CreateMobilePayment($cleanData);
        if($created === false)
            $error = 'Hubo un error al intentar registrar el pago móvil';
    }
}

// Managing feedback message and binnacle
if($error === ''){    
    if($edit){        
        $message = 'Pago móvil actualizado correctamente';

        $phoneChanged = $cleanData['phone'] !== $target_mobile_payment['phone'];
        $letterChanged = $cleanData['document_letter'] !== $target_mobile_payment['document_letter'];
        $numberChanged = $cleanData['document_number'] !== $target_mobile_payment['document_number'];
        $bankChanged = $cleanData['bank'] !== $target_mobile_payment['bank'];
        $activeChanged = intval($cleanData['active']) !== intval($target_transfer['active']);

        $action = 'Actualizó el pago móvil de id ' . $target_mobile_payment['id'];
        if($phoneChanged)
            $action .= '. Al teléono ' . $cleanData['phone'];

        if($letterChanged)
            $action .= '. A la letra de documento ' . $cleanData['document_letter'];

        if($numberChanged)
            $action .= '. Al número de documento ' . $cleanData['document_number'];

        if($bankChanged)
            $action .= '. Al banco ' . $cleanData['bank'];

        if($activeChanged)
            $action .= '. Al estado activo ' . $cleanData['active'];
    }
    else{
        $message = 'Pago móvil registrado correctamente';
        $action = 'Creo el pago móvil de teléfono ' . $cleanData['phone'] . ' con el documento ' . $cleanData['document_letter'] . '-' . $cleanData['document_number'] . ' banco: ' . $cleanData['bank'];
    }
    $mobile_payment_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){    
    if($edit)
        header("Location: $base_url/views/forms/mobile_payment_form.php?message=$message&id=" . $cleanData['id']);
    else
        header("Location: $base_url/views/forms/mobile_payment_form.php?message=$message&id=" . $created['id']);
}
else{
    if($edit){
        if($target_mobile_payment === false)
            header("Location: $base_url/views/tables/search_mobile_payment.php?error=$error");
        else
            header("Location: $base_url/views/forms/mobile_payment_form.php?error=$error&id=" . $target_mobile_payment['id']);
    }
    else
        header("Location: $base_url/views/forms/mobile_payment_form.php?error=$error");
}

exit;
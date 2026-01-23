<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';

$error = '';
$target_mobile_payment = false;

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
    include_once '../models/mobile_payments_model.php';
    $mobile_payment_model = new MobilePaymentsModel();

    if($edit){
        $target_mobile_payment = $mobile_payment_model->GetMobilePayment($id);
        if($target_mobile_payment === false)
            $error = 'Pago móvil no encontrado';
    }
}

if($error === ''){
    include_once '../fields_config/mobile_payments.php';
    $cleanData = Validator::ValidatePOSTFields($mobilePaymentFields);
    if(is_string($cleanData))
        $error = $cleanData;
}

if($error === ''){
    include_once '../models/bank_model.php';
    $bank_model = new BankModel();

    $target_bank = $bank_model->GetBankById($cleanData['bank']);
    if($target_bank === false)
        $error = 'Banco no encontrado';
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
            header("Location: $base_url/views/tables/search_mobile_payments.php?error=$error");
        else
            header("Location: $base_url/views/forms/mobile_payment_form.php?error=$error&id=" . $target_mobile_payment['id']);
    }
    else
        header("Location: $base_url/views/forms/mobile_payment_form.php?error=$error");
}

exit;
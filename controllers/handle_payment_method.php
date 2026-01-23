<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';

$error = '';
$target_payment_method = false;

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
    include_once '../models/payment_method_model.php';
    $payment_method_model = new PaymentMethodModel();

    if($edit){
        $target_payment_method = $payment_method_model->GetPaymentMethodType($id);
        if($target_payment_method === false)
            $error = 'Método de pago no encontrado';
    }
}

if($error === ''){
    include_once '../fields_config/payment_methods.php';
    $cleanData = Validator::ValidatePOSTFields($paymentMethodFields);
    if(is_string($cleanData))
        $error = $cleanData;
}

if($error === ''){
    $exists = $payment_method_model->GetPaymentMethodTypeByName($cleanData['name']);
    if($exists !== false){
        if($edit){
            if(intval($exists['id']) !== intval($id))
                $error = 'El nombre ingresado ya está registrado';
        }
        else
            $error = 'El nombre ingresado ya está registrado';
    }
}  

// Creating / updating the payment method
if($error === ''){    
    if($edit){
        $updated = $payment_method_model->UpdatePaymentMethodType($cleanData['id'], $cleanData);
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar el método de pago';
    }
    else{
        $created = $payment_method_model->CreatePaymentMethodType($cleanData);
        if($created === false)
            $error = 'Hubo un error al intentar registrar el método de pago';
    }
}

// Managing feedback message and binnacle
if($error === ''){
    if($edit){        
        $message = 'Método de pago actualizado correctamente';

        $nameChanged = $cleanData['name'] !== $target_payment_method['name'];

        $action = 'Actualizó el método de pago ' . $target_payment_method['name'];
        if($nameChanged)
            $action .= '. Al nombre ' . $cleanData['name'];

    }
    else{
        $message = 'Método de pago registrado correctamente';
        $action = 'Creo el método de pago ' . $cleanData['name'];
    }
    $payment_method_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){    
    if($edit)
        header("Location: $base_url/views/forms/payment_method_form.php?message=$message&id=" . $cleanData['id']);
    else
        header("Location: $base_url/views/forms/payment_method_form.php?message=$message&id=" . $created['id']);
}
else{
    if($edit){
        if($target_payment_method === false)
            header("Location: $base_url/views/tables/search_payment_method.php?error=$error");
        else
            header("Location: $base_url/views/forms/payment_method_form.php?error=$error&id=" . $target_payment_method['id']);
    }
    else
        header("Location: $base_url/views/forms/payment_method_form.php?error=$error");
}

exit;
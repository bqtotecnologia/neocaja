<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';

$error = '';
$target_global_var = false;

if(empty($_POST)){
    $error = 'POST vacío';
}

if($error === ''){
    $id = Validator::ValidateRecievedId('id', 'POST');
    if(is_string($id))
        $error = $id;
}

if($error === ''){
    include_once '../models/unknown_incomes_model.php';
    $unknown_model = new UnknownIncomesModel();

    $target_income = $unknown_model->GetUnknownIncome($id);
    if($target_income === false)
        $error = 'Ingreso no identificado no encontrado';
}

if($error === ''){
    $form = false;
    include_once '../fields_config/unknown_incomes.php';
    $cleanData = Validator::ValidatePOSTFields($unknownIncomeFields);
    if(is_string($cleanData))
        $error = $cleanData;
}

if($error === ''){
    if($cleanData['remote_payment'] !== ''){
        $payment_id = Validator::ValidateRecievedId('remote_payment', 'POST');
        if(is_string($payment_id))
            $error = $payment_id;
    }
}

if($error === ''){
    if($cleanData['remote_payment'] !== ''){
        include_once '../models/remote_payments_model.php';
        $remote_payment_model = new RemotePaymentsModel();

        $target_payment = $remote_payment_model->GetAccountPayment($payment_id);
        if($target_payment === false)
            $error = 'Pago remoto no encontrado';
    }
}

// Updating the unknown income
if($error === ''){
    if($payment_id === '')
        $payment_id = null;

    $updated = $unknown_model->SimpleUpdate('unknown_incomes', ['remote_payment' => $payment_id], $cleanData['id']);
    if($updated === false)
        $error = 'Hubo un error al intentar actualizar el ingreso no identificado';
}

// Managing feedback message and binnacle
if($error === ''){
    $action = "Actualizó el propietario del ingreso no identificado de id " . $cleanData['id'];
    $action .= ' al pago remoto ';
    if($payment_id === null)
        $action .= 'sin asignar';
    else
        $action .= 'de ' . $target_payment['fullname'] . ' de cédula ' . $target_payment['cedula'];

    $unknown_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){    
    header("Location: $base_url/views/forms/unknown_income_form.php?id=". $cleanData['id'] ."&message=Propietario de ingreso no identificado establecido correctamente");
} 
else{
    if(isset($cleanData['id'])){
        header("Location: $base_url/views/forms/unknown_income_form.php?id=". $cleanData['id'] ."&error=$error");
    }
    else{
        header("Location: $base_url/views/tables/search_unknown_incomes_by_date.php?error=$error");
    }
}

exit;
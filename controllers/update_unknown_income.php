<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';

include_once '../utils/base_url.php';
include_once '../utils/Validator.php';

$error = '';
$target_global_var = false;

if(empty($_POST)){
    $error = 'POST vacío';
}

$fields_config = [
    'id' => [
        'min' => 1,
        'max' => 11,
        'required' => true,
        'type' => 'numeric',
        'suspicious' => true,
    ],
    'account' => [
        'min' => 1,
        'max' => 11,
        'required' => true,
        'type' => 'numeric',
        'suspicious' => true,
    ],
];


$result = Validator::ValidatePOSTFields($fields_config);
if(is_string($result))
    $error = $result;
else
    $cleanData = $result;

if($error === ''){
    include_once '../models/unknown_incomes_model.php';
    $unknown_model = new UnknownIncomesModel();

    $target_income = $unknown_model->GetUnknownIncome($cleanData['id']);
    if($target_income === false)
        $error = 'Ingreso no identificado no encontrado';
}

if($error === ''){
    include_once '../models/account_model.php';
    $account_model = new AccountModel();

    $target_account = $account_model->GetAccount($cleanData['account']);
    if($target_account === false)
        $error = 'Cliente no encontrado';
}

// Updating the unknown income
if($error === ''){
    $updated = $unknown_model->SimpleUpdate('unknown_incomes', ['account' => $target_account['id']], $cleanData['id']);
    if($updated === false)
        $error = 'Hubo un error al intentar actualizar el valor de la variable global';
}


// Managing feedback message and binnacle
if($error === ''){
    $action = "Actualizó el propietario del ingreso no identificado de id " . $cleanData['id'];
    $action .= ' al cliente ' . $target_account['names'] . ' ' . $target_account['surnames'] . ' de cédula ' . $target_account['cedula'];

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
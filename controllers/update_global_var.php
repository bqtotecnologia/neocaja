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
        'max' => 50,
        'required' => true,
        'type' => 'numeric',
        'suspicious' => true,
    ],
    'value' => [
        'min' => 1,
        'max' => 14,
        'required' => true,
        'type' => 'float',
        'suspicious' => true,
    ],
];


$result = Validator::ValidatePOSTFields($fields_config);
if(is_string($result))
    $error = $result;
else
    $cleanData = $result;

if($error === ''){
    include_once '../models/global_vars_model.php';
    $global_var_model = new GlobalVarsModel();

    $target_global_var = $global_var_model->GetGlobalVar($cleanData['id']);
    if($target_global_var === false)
        $error = 'Variable global no encontrada';
}

// Updating the price
if($error === ''){
    $updated = $global_var_model->UpdateGlobalVar($cleanData['id'], $cleanData['value']);
    if($updated === false)
        $error = 'Hubo un error al intentar actualizar el valor de la variable global';
}


// Managing feedback message and binnacle
if($error === ''){
    $message = "Variable global actualizada correctamente";
    $action = "Variable global " . $target_global_var['name'] . " actualizada de " . $target_global_var['value'] . " a " . $cleanData['value'];

    $global_var_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){    
    header("Location: $base_url/views/tables/search_global_var.php?message=$message");
}
else{
    header("Location: $base_url/views/tables/search_global_var.php?error=$error");
}

exit;
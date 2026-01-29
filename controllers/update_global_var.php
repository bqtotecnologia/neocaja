<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';

$error = '';
$target_global_var = false;
$form = false;
if(empty($_POST)){
    $error = 'POST vacío';
}

if($error === ''){
    $id = Validator::ValidateRecievedId('id', 'POST');
    if(is_string($id))
        $error = $id;
}

if($error === ''){
    include_once '../models/global_vars_model.php';
    $global_var_model = new GlobalVarsModel();

    $target_global_var = $global_var_model->GetGlobalVar($id);
    if($target_global_var === false)
        $error = 'Variable global no encontrada';
}

if($error === ''){
    include_once '../fields_config/global_vars.php';
    $cleanData = Validator::ValidatePOSTFields($globalVarFields);
    if(is_string($cleanData))
        $error = $cleanData;
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
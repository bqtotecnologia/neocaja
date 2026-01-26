<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';

$error = '';
$target_sale_point = false;

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
    include_once '../models/sale_point_model.php';
    $sale_point_model = new SalePointModel();

    if($edit){
        $target_sale_point = $sale_point_model->GetSalePoint($id);
        if($target_sale_point === false)
            $error = 'Punto de venta no encontrada';
    }
}

if($error === ''){
    include_once '../fields_config/sale_points.php';
    $cleanData = Validator::ValidatePOSTFields($salePointFields);
    if(is_string($cleanData))
        $error = $cleanData;
}

if($error === ''){
    $exists = $sale_point_model->GetSalePointByCode($cleanData['code']);
    if($exists !== false){
        if($edit){
            if(intval($target_sale_point['id']) !== intval($exists['id']))
                $error = 'El código ingresado ya está registrado';
        }
        else
            $error = 'El código ingresado ya está registrado';
    }
}  

if($error === ''){
    include_once '../models/bank_model.php';
    $bank_model = new BankModel();

    $bankId = Validator::ValidateRecievedId('bank', 'POST');
    if(is_string($bankId))
        $error = $bankId;
}

if($error === ''){
    $target_bank = $bank_model->GetBankById($bankId);
    if($target_bank === false)
        $error = 'Banco no encontrado';
}

// Creating / updating the sale point
if($error === ''){    
    if($edit){
        $updated = $sale_point_model->UpdateSalePoint($cleanData['id'], $cleanData);
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar el punto de venta';
    }
    else{
        $created = $sale_point_model->CreateSalePoint($cleanData);
        if($created === false)
            $error = 'Hubo un error al intentar registrar el punto de venta';
    }
}

// Managing feedback message and binnacle
if($error === ''){
    if($edit){        
        $message = 'Punto de venta actualizado correctamente';

        $codeChanged = $cleanData['code'] !== $target_sale_point['code'];

        $action = 'Actualizó el punto de venta ' . $target_sale_point['code'];

        if($codeChanged)
            $action .= '. Al código ' . $cleanData['code'];
    }
    else{
        $message = 'Punto de venta registrado correctamente';
        $action = 'Creo el punto de venta ' . $cleanData['code'];
    }
    $sale_point_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){    
    if($edit)
        header("Location: $base_url/views/forms/sale_point_form.php?message=$message&id=" . $cleanData['id']);
    else
        header("Location: $base_url/views/forms/sale_point_form.php?message=$message&id=" . $created['id']);
}
else{
    if($edit){
        if($target_sale_point === false)
            header("Location: $base_url/views/tables/search_sale_point.php?error=$error");
        else
            header("Location: $base_url/views/forms/sale_point_form.php?error=$error&id=" . $target_sale_point['id']);
    }
    else
        header("Location: $base_url/views/forms/sale_point_form.php?error=$error");
}

exit;
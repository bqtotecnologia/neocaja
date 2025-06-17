<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';

include_once '../utils/base_url.php';
include_once '../utils/Validator.php';

$error = '';
$target_sale_point = false;

if(empty($_POST)){
    $error = 'POST vacío';
}

$fields_config = [
    'code' => [
        'min' => 1,
        'max' => 8,
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
    include_once '../models/sale_point_model.php';
    $sale_point_model = new SalePointModel();

    if($edit){
        $target_sale_point = $sale_point_model->GetSalePoint($cleanData['id']);
        if($target_sale_point === false)
            $error = 'Punto de venta no encontrada';
    }
    else{
        $target_sale_point = $sale_point_model->GetSalePointByCode($cleanData['code']);
        if($target_sale_point !== false)
            $error = 'El código del punto de venta está repetido';
    }
}

if($error === ''){
    if($edit){
        if($target_sale_point['id'] !== $cleanData['id'] && $target_sale_point['code'] === $cleanData['code'])
            $error = 'El código ingresado ya está registrado';
    }
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
<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';

include_once '../utils/base_url.php';
include_once '../utils/Validator.php';

$validator = new Validator();

$error = '';
if(empty($_POST)){
    $error = 'POST vacío';
}

$fields_config = [
    'name' => [
        'min' => 1,
        'max' => 255,
        'required' => true,
        'type' => 'string',
        'suspicious' => true,
    ],
    'price' => [
        'min' => 1,
        'max' => 12,
        'required' => true,
        'type' => 'float',
        'suspicious' => true,
    ],
];

$result = $validator->ValidatePOSTFields($fields_config);
if(is_string($result))
    $error = $result;
else
    $cleanData = $result;

$edit = isset($cleanData['id']);

if($error === ''){
    include_once '../models/product_model.php';
    $product_model = new ProductModel();

    if($edit){
        $target_product = $product_model->GetProduct($cleanData['id']);
        if($target_product === false)
            $error = 'Producto no encontrado';
    }
    else{
        $target_product = $product_model->GetProductByName($cleanData['name']);
        if($target_product !== false)
            $error = 'El nombre del producto está repetido';
    }   
}

if($error === ''){
    if($edit){
        $same_name = $product_model->GetProductByName($cleanData['name']);
        if($same_name['name'] === $target_product['name'] && intval($target_product['id']) !== $cleanData['id'])
            $error = 'El nombre del producto está repetido';
    }    
}    

// Creating the product
if($error === ''){
    if($edit){
        $updated = $product_model->UpdateProduct($cleanData, $cleanData['id']);
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar el producto';
    }
    else{
        $created = $product_model->CreateProduct($cleanData);
        if($created === false)
            $error = 'Hubo un error al intentar registrar el producto';
    }
}

// Creating the price changing register
if($error === ''){
    if($edit){
        if($cleanData['price'] !== floatval($target_product['price'])){
            $updated = $product_model->UpdateProductPrice($cleanData['price'], $cleanData['id']);
            if($updated === false)
                $error = 'Ocurrió un error al intentar actualizar el precio del producto';
            else{
                $message = 'Producto actualizado correctamente';
                $action = 'Actualizó el producto ' . $cleanData['name'];
                $product_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
            }
        }
    }
    else{
        $updated = $product_model->UpdateProductPrice($cleanData['price'], $created['id']);
        if($updated === false)
            $error = 'Ocurrió un error al intentar establecer el precio del producto';
        else{
            $message = 'Producto registrado correctamente';
            $action = 'Creo el producto ' . $cleanData['name'];
            $product_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
        }
    }
}

if($error !== ''){
    if($edit){
        header("Location: $base_url/views/forms/product_form.php?error=$error&id=" . $cleanData['id']);
    }
    else{
        header("Location: $base_url/views/forms/product_form.php?error=$error");
    }
    exit;
}
else{
    header("Location: $base_url/views/tables/search_product.php?message=$message&id=" . $created['id']);
    exit;
}

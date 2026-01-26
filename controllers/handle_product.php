<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';

$error = '';
$form = false;
$edit = isset($_POST['id']);
$target_product = false;

if(empty($_POST)){
    $error = 'POST vacío';
}

if($error === '' && $edit){
    $id = Validator::ValidateRecievedId('id', 'POST');
    if(is_string($id))
        $error = $id;
}

if($error === ''){
    include_once '../models/product_model.php';
    $product_model = new ProductModel();

    if($edit){
        $target_product = $product_model->GetProduct($id);
        if($target_product === false)
            $error = 'Producto no encontrado';
    }
}

if($error === ''){
    include_once '../fields_config/products.php';
    $cleanData = Validator::ValidatePOSTFields($productFields);
    if(is_string($cleanData))
        $error = $cleanData;
}

if($error === ''){
    $exists = $product_model->GetProductByName($cleanData['name']);
    if($exists !== false){
        if($edit){
            if(intval($exists['id']) !== intval($id))
                $error = 'El nombre del producto está repetido';
        }else
            $error = 'El nombre del producto está repetido';
    }
}    

// Creating / updating the product
if($error === ''){    
    $cleanData['active'] = isset($_POST['active']) ? '1' : '0';

    if($edit){
        $updated = $product_model->UpdateProduct($cleanData['id'], $cleanData);
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
    $priceChanged = false;
    if($edit){
        if($cleanData['price'] !== floatval($target_product['price'])){
            $priceChanged = true;

            $updated = $product_model->UpdateProductPrice($cleanData['price'], $cleanData['id']);
            if($updated === false)
                $error = 'Ocurrió un error al intentar actualizar el precio del producto';
        }
    }
    else{
        $updated = $product_model->UpdateProductPrice($cleanData['price'], $created['id']);
        if($updated === false)
            $error = 'Ocurrió un error al intentar establecer el precio del producto';
    }
}

// Managing feedback message and binnacle
if($error === ''){
    if($edit){        
        $message = 'Producto actualizado correctamente';

        $activeChanged = $cleanData['active'] !== $target_product['active'];
        $nameChanged = $cleanData['name'] !== $target_product['name'];

        $action = 'Actualizó el producto ' . $target_product['name'];
        if($nameChanged)
            $action .= '. Al nombre ' . $cleanData['name'];

        if($activeChanged)
            $action .= '. Al estado activo ' . ($cleanData['active'] === '1' ? 'Si' : 'No');

        if($priceChanged)
            $action .= '. Al precio ' . $cleanData['price'];
    }
    else{
        $message = 'Producto registrado correctamente';
        $action = 'Creo el producto ' . $cleanData['name'] . ' con el precio ' . $cleanData['price'];
    }
    $product_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){    
    if($edit)
        header("Location: $base_url/views/forms/product_form.php?message=$message&id=" . $cleanData['id']);
    else
        header("Location: $base_url/views/forms/product_form.php?message=$message&id=" . $created['id']);
}
else{
    if($edit){
        if($target_product === false)
            header("Location: $base_url/views/tables/search_product.php?error=$error");
        else
            header("Location: $base_url/views/forms/product_form.php?error=$error&id=" . $target_product['id']);
    }
    else
        header("Location: $base_url/views/forms/product_form.php?error=$error");
}

exit;
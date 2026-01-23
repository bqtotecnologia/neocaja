<?php

/**
 * For register a new coin you need a API service that returns 2 values 'success' and 'value'
 * The 'success' are True or False
 * The 'value' if 'success' is True, will be the coin price, otherwise will be a string error message
 */
$admitted_user_types = ['Tecnología', 'Super', 'Cajero'];
include_once '../utils/validate_user_type.php';

include_once '../utils/base_url.php';
include_once '../utils/Validator.php';

$error = '';
$target_coin = false;
$edit = isset($_POST['id']);

if(empty($_POST)){
    $error = 'POST vacío';
}

if($error === '' && $edit){
    $id = Validator::ValidateRecievedId('id', 'POST');
    if(is_string($id))
        $error = $id;
}

if($error === ''){
    include_once '../models/coin_model.php';
    $coin_model = new CoinModel();

    if($edit){
        $target_coin = $coin_model->GetCoin($id);
        if($target_coin === false)
            $error = 'Moneda no encontrada';
    }
    else{
        $exists = $coin_model->GetCoinByName($cleanData['name']);
        if($exists !== false)
            $error = 'El nombre de la moneda está repetido';
    }   
}

if($error === ''){
    include_once '../utils/Auth.php';
    include_once '../fields_config/coins.php';
    $cleanData = Validator::ValidatePOSTFields($coinFields);
    if(is_string($cleanData))
        $error = $cleanData;
}

if($error === ''){    
    $checkAPI = false;
    if($edit){
        $urlChanged = false;
        if($cleanData['url'] !== $target_coin['url']){
            $urlChanged = true;
            $checkAPI = true;
        }
    }
    else{
        $checkAPI = true;
    }

    // Como la API no funciona, siempre se va a obviar la validación de la API, si se desea habilitar, comente la siguiente línea de código
    $checkAPI = false;
}

// Checking if the url API works
if($error === ''){
    $cleanData['auto_update'] = isset($_POST['auto_update']) ? '1' : '0';
    if($checkAPI && $cleanData['auto_update'] === '1'){
        $url = $cleanData['url'];
        $API_result = Validator::ValidateCoinAPI($url);
        if(is_string($API_result))
            $error = $API_result;
    }
}

if($error === ''){
    if(isset($API_result))
        $coinValue = $API_result['value'];
    else
        $coinValue = "0.0000";
}

// Creating / updating the coin
if($error === ''){        
    $cleanData['active'] = isset($_POST['active']) ? '1' : '0';    
    $cleanData['url'] = $_POST['url'] ?? '';

    if($edit){
        $updated = $coin_model->UpdateCoin($cleanData['id'], $cleanData);
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar la moneda';
    }
    else{
        $created = $coin_model->CreateCoin($cleanData);
        if($created === false)
            $error = 'Hubo un error al intentar registrar la moneda';
    }
}

// Creating the price changing register
if($error === ''){
    $priceChanged = false;
    if($edit){
        if($urlChanged && isset($API_result)){
            if(floatval($coinValue) !== floatval($target_coin['price'])){
                $priceChanged = true;
    
                $updated = $coin_model->UpdateCoinPrice($coinValue, $cleanData['id']);
                if($updated === false)
                    $error = 'Ocurrió un error al intentar actualizar el precio de la moneda';
            }
        }
    }
    else{
        $updated = $coin_model->UpdateCoinPrice($coinValue, $created['id']);
        if($updated === false)
            $error = 'Ocurrió un error al intentar establecer el precio de la moneda';
    }
}

// Managing feedback message and binnacle
if($error === ''){
    if($edit){        
        $message = 'Moneda actualizada correctamente';

        $activeChanged = $cleanData['active'] !== $target_coin['active'];
        $nameChanged = $cleanData['name'] !== $target_coin['name'];
        $urlChanged = $cleandata['url'] !== $target_coin['url'];

        $action = 'Actualizó la moneda ' . $target_coin['name'];
        if($nameChanged)
            $action .= '. Al nombre ' . $cleanData['name'];

        if($activeChanged)
            $action .= '. Al estado activo ' . ($cleanData['active'] === '1' ? 'Si' : 'No');

        if($urlChanged)
            $action .= '. A la url ' . $cleanData['url'];

        if($priceChanged)
            $action .= '. que retornó un precio de ' . $coinValue;

    }
    else{
        $message = 'Moneda registrada correctamente';
        $action = 'Creo la moneda ' . $cleanData['name'] . ' con la URL ' . $cleanData['url'] . ' que retornó un precio de' . $coinValue;
    }
    $coin_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}


if($error === ''){    
    if($edit)
        header("Location: $base_url/views/forms/coin_form.php?message=$message&id=" . $id);
    else
        header("Location: $base_url/views/forms/coin_form.php?message=$message&id=" . $created['id']);
}
else{
    if($edit){
        if($target_coin === false)
            header("Location: $base_url/views/tables/search_coin.php?error=$error");
        else
            header("Location: $base_url/views/forms/coin_form.php?error=$error&id=" . $id);
    }
    else
        header("Location: $base_url/views/forms/coin_form.php?error=$error");
}

exit;
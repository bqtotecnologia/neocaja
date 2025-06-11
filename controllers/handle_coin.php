<?php

/**
 * For register a new coin you need a API service that returns 2 values 'success' and 'result'
 * The 'success' are True or False
 * The 'result' if 'success' is True, will be the coin price, otherwise will be a error message
 */
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';

include_once '../utils/base_url.php';
include_once '../utils/Validator.php';

$error = '';
$target_coin = false;

if(empty($_POST)){
    $error = 'POST vacío';
}

$fields_config = [
    'name' => [
        'min' => 1,
        'max' => 50,
        'required' => true,
        'type' => 'string',
        'suspicious' => true,
    ],
    'price' => [
        'min' => 1,
        'max' => 12,
        'required' => false,
        'type' => 'float',
        'suspicious' => true,
    ],
    'url' => [
        'min' => 1,
        'max' => 255,
        'required' => true,
        'type' => 'text',
        'suspicious' => false,
    ],
];

$result = Validator::ValidatePOSTFields($fields_config);
if(is_string($result))
    $error = $result;
else
    $cleanData = $result;

$edit = isset($_POST['id']);

if($error === ''){
    include_once '../models/coin_model.php';
    $coin_model = new CoinModel();

    if($edit){
        $target_coin = $coin_model->GetCoin($cleanData['id']);
        if($target_coin === false)
            $error = 'Moneda no encontrada';
    }
    else{
        $target_coin = $coin_model->GetCoinByName($cleanData['name']);
        if($target_coin !== false)
            //$error = 'El nombre de la moneda está repetido';
            $error = '';
    }   
}

if($error === ''){
    if($edit){
        if($cleanData['name'] === $target_coin['name'] && intval($target_coin['id']) !== $cleanData['id'])
            $error = 'El nombre de la moneda está repetido';
    }    
}    

if($error === ''){
    $urlChanged = false;
    if($edit){
        if($cleanData['url'] !== $target_coin['url'])
            $urlChanced = true;
    }
}

// Checking if the url API works
$checkAPI = false;
if($error === ''){    
    // If you change the URL or create a new coin, check the API
    if(!$edit || $urlChanced){
        $checkAPI = true;
        $url = $cleanData['url'];
        try {
            $response = file_get_contents($url);
            $json = json_decode($response, true);
        } catch (Exception $e) {
            //var_dump($e);
            //exit;
            $error = 'Ocurrió un error al intentar consultar la API';
        }        
    }
}



if($error === '' && $checkAPI){
    if(!isset($json['success']))
        $error = 'La API no retorna un valor "success"';
    else{
        if($json['success'] !== false && $json['success'] !== true)
            $error = 'El valor success de la API no retorna un valor true o false';
        else{
            $coinResponse = $json['success'];
        }
    }
}

if($error === '' && $checkAPI){
    if(!isset($json['result']))
        $error = 'La API no retorna un valor "result"';
    else{
        if(!is_numeric($json['result'])){
            if($coinResponse === true)
                $error = 'El valor result arrojado por la API no es numérico';
            else
                $error = $json['result'];
        }
        else{
            $coinValue = $json['result'];
            $decimalCount = Validator::GetDecimalCountOfFloat($coinValue);
            if($decimalCount !== 4)
                $error = 'La parte decimal de la moneda debe tener 4 decimales. Valor obtenido: ' . $coinValue;
        }
    }
}

if($error === '' && $checkAPI){
    if($coinResponse === false)
        $error = $json['result'];
}

var_dump($cleanData);
// TODO: Pensar en si se debe priorizar actualizar el precio desde la API o permitir que el usuario la pueda cambiar manualmente

if($error === ''){
    if($edit){
        $decimalCount = Validator::GetDecimalCountOfFloat($cleandata['price']);
        if($decimalCount !== 4)
            $error = 'La parte decimal de la moneda debe tener 4 decimales. Valor obtenido: ' . $cleandata['price'];
    }
}


if($error === ''){
    var_dump($coinResponse); echo '<br>';
    var_dump($coinValue); echo '<br>';

}
else{
    var_dump($error); echo '<br>';
}

exit;

// Creating / updating the coin
if($error === ''){    
    $cleanData['active'] = isset($_POST['active']) ? '1' : '0';

    if($edit){
        $updated = $coin_model->UpdateCoin($cleanData['id'], $cleanData);
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar la moneda';
    }
    else{
        //$created = $coin_model->CreateCoin($cleanData);
        $created = true;
        if($created === false)
            $error = 'Hubo un error al intentar registrar la moneda';
    }
}

// Creating the price changing register
if($error === ''){
    $priceChanged = false;
    if($edit){
        if($cleanData['price'] !== floatval($target_coin['price'])){
            $priceChanged = true;

            $updated = $coin_model->UpdateCoinPrice($cleanData['price'], $cleanData['id']);
            if($updated === false)
                $error = 'Ocurrió un error al intentar actualizar el precio del producto';
        }
    }
    else{
        $updated = $coin_model->UpdateCoinPrice($coinValue, $created['id']);
        if($updated === false)
            $error = 'Ocurrió un error al intentar establecer el precio del producto';
    }
}

// Managing feedback message and binnacle
if($error === ''){
    if($edit){        
        $message = 'Moneda actualizada correctamente';

        $activeChanged = $cleanData['active'] !== $target_coin['active'];
        $nameChanged = $cleanData['name'] !== $target_coin['name'];
        $urlChanced = $cleandata['url'] !== $target_coin['url'];

        $action = 'Actualizó la moneda ' . $target_coin['name'];
        if($nameChanged)
            $action .= '. Al nombre ' . $cleanData['name'];

        if($activeChanged)
            $action .= '. Al estado activo ' . ($cleanData['active'] === '1' ? 'Si' : 'No');

        if($priceChanged)
            $action .= '. Al precio ' . $cleanData['price'];

        if($urlChanced)
            $action .= '. A la url ' . $cleanData['url'];
    }
    else{
        $message = 'Producto registrado correctamente';
        $action = 'Creo el producto ' . $cleanData['name'] . ' con el precio ' . $cleanData['price'];
    }
    $coin_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}


var_dump($error);
exit;

if($error === ''){    
    if($edit)
        header("Location: $base_url/views/forms/product_form.php?message=$message&id=" . $cleanData['id']);
    else
        header("Location: $base_url/views/forms/product_form.php?message=$message&id=" . $created['id']);
}
else{
    if($edit){
        if($target_coin === false)
            header("Location: $base_url/views/tables/search_product.php?error=$error");
        else
            header("Location: $base_url/views/forms/product_form.php?error=$error&id=" . $target_coin['id']);
    }
    else
        header("Location: $base_url/views/forms/product_form.php?error=$error");
}

exit;
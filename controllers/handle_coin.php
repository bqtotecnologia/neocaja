<?php

/**
 * For register a new coin you need a API service that returns 2 values 'success' and 'result'
 * The 'success' are True or False
 * The 'result' if 'success' is True, will be the coin price, otherwise will be a error message
 */
$admitted_user_types = ['Tecnología', 'Super'];
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
            $error = 'El nombre de la moneda está repetido';
    }   
}

if($error === ''){
    if($edit){
        if($cleanData['name'] === $target_coin['name'] && intval($target_coin['id']) !== $cleanData['id'])
            $error = 'El nombre de la moneda está repetido';
    }    
}    

if($error === ''){    
    $checkAPI = false;
    if($edit){
        $urlChanged = false;
        if($cleanData['url'] !== $target_coin['url']){
            $urlChanced = true;
            $checkAPI = true;
        }
    }
    else{
        $checkAPI = true;
    }
}

// Checking if the url API works
if($error === ''){
    if($checkAPI){
        $url = $cleanData['url'];
        $result = Validator::ValidateCoinAPI($url);
        if(is_string($result))
            $error = $result;
    }
}

// Creating / updating the coin
if($error === ''){    
    $cleanData['active'] = isset($_POST['active']) ? '1' : '0';

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
        if($urlChanced){
            if($coinValue !== floatval($target_coin['price'])){
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
        $urlChanced = $cleandata['url'] !== $target_coin['url'];

        $action = 'Actualizó la moneda ' . $target_coin['name'];
        if($nameChanged)
            $action .= '. Al nombre ' . $cleanData['name'];

        if($activeChanged)
            $action .= '. Al estado activo ' . ($cleanData['active'] === '1' ? 'Si' : 'No');

        if($urlChanced)
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
        header("Location: $base_url/views/forms/coin_form.php?message=$message&id=" . $cleanData['id']);
    else
        header("Location: $base_url/views/forms/coin_form.php?message=$message&id=" . $created['id']);
}
else{
    if($edit){
        if($target_coin === false)
            header("Location: $base_url/views/tables/search_coin.php?error=$error");
        else
            header("Location: $base_url/views/forms/coin_form.php?error=$error&id=" . $target_coin['id']);
    }
    else
        header("Location: $base_url/views/forms/coin_form.php?error=$error");
}

exit;
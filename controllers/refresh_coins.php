<?php
/**
 * Este archivo actualiza las tasas de todas las monedas, sin embargo para ello se debe llamar a este archivo
 * habiendo declarado previamente una variable $allow_refresh.
 * 
 * Este contiene una variable $coins_refreshed que empieza en false y termina en true si todo el script se ejecuta.
 * También al final crea una variable $message con los mensajes de error o éxito de la operación
 */
include_once '../utils/base_url.php';

$coins_refreshed = false;
if(!isset($allow_refresh)){
    header("Location: $base_url/views/forms/login.php");
    return;
}

include_once '../utils/Validator.php';

include_once '../models/coin_model.php';
$coin_model = new CoinModel();

$not_updated_coins = $coin_model->GetNotUpdatedCoins(false);

if($not_updated_coins === []){
    return;
}

$error = '';
$message = '';
$data = [];
foreach($not_updated_coins as $coin){
    if($coin['auto_update'] === '0') 
        continue;

    $result = Validator::ValidateCoinAPI($coin['url']);
    if(is_string($result)){
        $error .= $result . ' en la moneda ' . $coin['name'] . '<br>';
        continue;
    }

    $to_add = [
        'coin' => $coin['id'],
        'name' => $coin['name'],
        'price' => $result['value']
    ];
    array_push($data, $to_add);
}

foreach($data as $d){
    $updated = $coin_model->UpdateCoinPrice($d['price'], $d['coin']);
    if($updated === false)
        $error .= 'Hubo un error en la base de datos al intentar actualizar el precio de la moneda ' . $d['name'] . '<br>';
    else
        $message .= 'Moneda ' . $d['name'] . ' actualizada correctamente<br>';
}

if($error === '')
    $refresh_message = 'Todas las monedas han sido actualizadas correctamente';
else{
    $refresh_message = $message . $error;
}

$coins_refreshed = true;
<?php
$admitted_user_types = ['Cajero', 'Tecnología', 'Super'];
include_once '../utils/validate_user_type.php';
include_once '../utils/base_url.php';
include_once '../utils/Validator.php';

include_once '../models/coin_model.php';
$coin_model = new CoinModel();

$not_updated_coins = $coin_model->GetNotUpdatedCoins();

if($not_updated_coins === []){
    header("Location: $base_url/views/forms/refresh_coins.php");
    exit;
}

$error = '';
$message = '';
$data = [];
foreach($not_updated_coins as $coin){
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
    $message = 'Todas las monedas han sido actualizadas correctamente';
else{
    $message = $message . $error;
}

$redirect = "Location: $base_url/views/panel.php?a=1&";
if($error === '')
    $redirect .= 'message=';
else
    $redirect .= 'error=';

$redirect .= $message;

header($redirect);
exit;
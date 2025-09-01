<?php
include_once '../utils/Auth.php';
$admitted_user_types = ['Cajero', 'Super'];
session_start();
$userOk = Auth::UserLevelIn($admitted_user_types);

$error = '';
if($userOk === false)
    $error = 'Permiso denegado. Cierre sesión e inicie nuevamente';

include_once '../utils/Validator.php';

if(empty($_GET)){
    $error = 'GET vacío';
}

if(!isset($_GET['date'])){
    $error = 'Fecha no encontrada';
}

try{
    $target_date = new DateTime($_GET['date']);
}
catch(Exception $e){
    $error = 'Fecha inválida';
}

if($error === ''){
    include_once '../models/coin_model.php';
    $coin_model = new CoinModel();
    $coins = $coin_model->GetActiveCoins();
    $coin_values = $coin_model->GetCoinValuesOfDate($target_date->format('Y-m-d')) ;
    if($coin_values === false)
        $error = 'No hay tasas registradas en al fecha dada';
}

if($error === ''){
    $ordered_coins = [];
    foreach($coins as $coin){
        if($coin['name'] === 'Bolívar')
            $ordered_coins[$coin['name']] = 1;
        else
            $ordered_coins[$coin['name']] = 0;
    }

    foreach($coin_values as $coin){
        $ordered_coins[$coin['name']] = floatval($coin['price']);
    }
}

if($error === ''){
    $response = [
        'status' => true,
        'data' => $ordered_coins
    ];
}else{
    $response = [
        'status' => false,
        'message' => $error
    ];
}

$json_data = json_encode($response, JSON_UNESCAPED_UNICODE); // Para que acepte las tildes
header('Content-Type: application/json');
echo $json_data;

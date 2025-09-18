<?php
include_once '../utils/Auth.php';
$admitted_user_types = ['Cajero', 'Super'];
session_start();
$userOk = Auth::UserLevelIn($admitted_user_types);

$error = '';
if($userOk === false)
    $error = 'Permiso denegado. Cierre sesión e inicie nuevamente';

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
    $ordered_coins = $coin_model->GetCoinValuesOfDate($target_date->format('Y-m-d')) ;
    if($ordered_coins === false)
        $error = 'No hay tasas registradas en al fecha dada';
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

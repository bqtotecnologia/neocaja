<?php
$admitted_user_types = ['Estudiante', 'Super', 'Tecnologia', 'Cajero', 'SENIAT'];
include_once '../utils/Auth.php';
session_start();

$error = '';
if(Auth::UserLevelIn($admitted_user_types) === false)
    $error = 'Permiso denegado. Cierre sesión e inicie nuevamente';

if($error === ''){
    $inputJSON = file_get_contents('php://input');
    $post = json_decode($inputJSON, TRUE);
    
    if($post === NULL){
        $error = 'POST vacío';
    }
}

if($error === ''){
    if(!isset($post['date'])){
        $error = 'Campos necesarios no recibidos';
    }
}
if($error === ''){
    try{
        $target_date = new DateTime($post['date']);
    }
    catch(Exception $e){
        $error = 'Fecha inválida';
    }
}

if($error === ''){
    include_once '../models/coin_model.php';
    $coin_model = new CoinModel();
    $usd = $coin_model->GetCoinByName('Dólar');

    if($usd === false)
        $error = 'La moneda dólar no existe';
}

if($error === ''){
    $usd_value = $coin_model->GetCoinPriceOfDateById($usd['id'], $post['date']);
    if($usd_value === false)
        $error = 'No hay una tasa registrada para la fecha seleccionada. De haber realizado el pago el ' . $target_date->format('d/m/Y') . ' acuda al instituto para procesar el pago manualmente.';
}

if($error === ''){
    $response = [
        'status' => true,
        'data' => $usd_value['price']
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

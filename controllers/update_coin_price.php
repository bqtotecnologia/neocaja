<?php
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
    'coin' => [
        'min' => 1,
        'max' => 50,
        'required' => true,
        'type' => 'numeric',
        'suspicious' => true,
    ],
    'price' => [
        'min' => 1,
        'max' => 14,
        'required' => true,
        'type' => 'float',
        'suspicious' => true,
    ],
    'date' => [
        'min' => 10,
        'max' => 11,
        'required' => true,
        'type' => 'date',
        'suspicious' => false,
    ],
];


$result = Validator::ValidatePOSTFields($fields_config);
if(is_string($result))
    $error = $result;
else
    $cleanData = $result;

if($error === ''){
    include_once '../models/coin_model.php';
    $coin_model = new CoinModel();

    $target_coin = $coin_model->GetCoin($cleanData['coin']);
    if($target_coin === false)
        $error = 'Moneda no encontrada';
}

if($error === ''){
    $decimalCount = Validator::GetDecimalCountOfFloat(strval($_POST['price']));
    if($decimalCount === false)
        $error = 'La tasa de la moneda debe ser decimal';
    else{
        if($decimalCount < 4)
            $error = 'La tasa debe tener al menos 4 decimales';
    }
}

if($error === ''){
    if(strtotime($cleanData['date']->format('Y-m-d')) > strtotime(date('Y-m-d'))){
        $error = 'No se puede actualizar la tasa en un día posterior a hoy';
    }
}

// Updating the price
if($error === ''){
    $today = ($cleanData['date']->format('Y-m-d')) === date('Y-m-d');
    if($today){
        $updated = $coin_model->UpdateCoinPrice($cleanData['price'], $target_coin['id']);
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar la tasa de la moneda del día de hoy';
    }
    else{        
        $updated = $coin_model->UpdateCoinPriceOfDate($target_coin['id'], $cleanData['price'], $cleanData['date']->format('Y-m-d'));
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar la tasa antigua de la moneda';
    }
}


// Managing feedback message and binnacle
if($error === ''){
    $message = "Tasa del " . $coin['name'] . " ha sido actualizada ";
    if($today){
        $message .= "al día de hoy manualmente";
        $action = "Actualizó la tasa del " . $coin['name'] . " al día en curso con un valor de " . $cleanData['price'];
    }else{
        $message .= "en la fecha dada";
        $action = 'Atualizó la tasa del ' . $coin['name'] . " de la fecha " . $cleanData['date']->format('d/m/Y') . " con un valor de " . $cleanData['price'];
    }

    $coin_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){    
    header("Location: $base_url/views/tables/search_coin.php?message=$message");
}
else{
    header("Location: $base_url/views/tables/search_coin.php?error=$error");
}

exit;
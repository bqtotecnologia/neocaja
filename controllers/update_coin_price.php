<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';

$error = '';
$target_coin = false;
$form = false;

if(empty($_POST)){
    $error = 'POST vacío';
}

if($error === ''){
    include_once '../fields_config/coins_price.php';
    $cleanData = Validator::ValidatePOSTFields($coinPriceFields);
    if(is_string($cleanData))
        $error = $cleanData;
}

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

if($error === ''){
    // Verificamos si el día actualizado es un Lunes, de ser así se le coloca esa misma tasa al domingo y sábado
    if($cleanData['date']->format('D') === 'Mon'){
        $cleanData['date']->modify('-1 day'); // Domingo
        $coin_model->UpdateCoinPriceOfDate($target_coin['id'], $cleanData['price'], $cleanData['date']->format('Y-m-d'));
        $cleanData['date']->modify('-1 day'); // Sábado
        $coin_model->UpdateCoinPriceOfDate($target_coin['id'], $cleanData['price'], $cleanData['date']->format('Y-m-d'));
    }
}


// Managing feedback message and binnacle
if($error === ''){
    $message = "Tasa del " . $target_coin['name'] . " ha sido actualizada ";
    $action = 'Atualizó la tasa del ' . $target_coin['name'] . " con un valor de " . $cleanData['price'] . " para la fecha " . $cleanData['date']->format('d/m/Y');
    $coin_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){    
    header("Location: $base_url/views/tables/search_coin.php?message=$message");
}
else{
    header("Location: $base_url/views/forms/update_coin_price.php?error=$error");
}

exit;
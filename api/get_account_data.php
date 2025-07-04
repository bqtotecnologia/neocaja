<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';

include_once '../utils/base_url.php';
include_once '../utils/Validator.php';

$error = '';
if(empty($_GET)){
    $error = 'GET vacío';
}

if($error === ''){
    $account = Validator::ValidateRecievedId('account');
    if(is_string($account))
        $error = 'Id del cliente inválido';
}

if($error === ''){
    include_once '../models/account_model.php';
    $account_model = new AccountModel();
    $target_account = $account_model->GetAccount($account);
    if($target_account === false)
        $error = 'Cliente no encontrado';
}

if($error === ''){
    $response = [
        'status' => true,
        'data' => $target_account
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

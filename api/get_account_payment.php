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

if($error === ''){
    $id = Validator::ValidateRecievedId();
    if(is_string($id))
        $error = 'Id del cliente inválido';
}

if($error === ''){
    include_once '../models/account_payments_model.php';
    $account_payments_model = new AccountPaymentsModel();
    $target_payment = $account_payments_model->GetAccountPayment($id);
    if($target_payment === false)
        $error = 'Pago remoto no encontrado';
}

if($error === ''){
    $products = $account_payments_model->GetProductsOfPayment($id);
    $payment_method = $account_payments_model->GetPaymentMethodOfPayment($id);
    $response = [
        'status' => true,
        'data' => [
            'payment' => $target_payment,
            'payment_method' => $payment_method,
            'products' => $products
        ]
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

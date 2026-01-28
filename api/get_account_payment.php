<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/Auth.php';
session_start();

$error = '';
if(Auth::UserLevelIn($admitted_user_types) === false)
    $error = 'Permiso denegado. Cierre sesión e inicie nuevamente';

if($error === ''){    
    if(empty($_GET)){
        $error = 'GET vacío';
    }
}

if($error === ''){
    include_once '../utils/Validator.php';
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
    $payment_method = $account_payments_model->GetPaymentMethodOfPayment($target_payment);
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

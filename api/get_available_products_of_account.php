<?php
include_once '../utils/Auth.php';
$admitted_user_types = ['Estudiante', 'Super'];
session_start();
$userOk = Auth::UserLevelIn($admitted_user_types);

$error = '';
if($userOk === false)
    $error = 'Permiso denegado. Cierre sesión e inicie nuevamente';

include_once '../utils/Validator.php';

if(empty($_POST)){
    $error = 'POST vacío';
}

if($error === ''){
    $cedula = Validator::HasSuspiciousCharacters($_POST['cedula']);
    if($cedula === true)
        $error = 'Cédula inválida';
}

if($error === ''){
    $period = Validator::HasSuspiciousCharacters($_POST['period']);
    if($period === true)
        $error = 'Periodo inválido';
}

if($error === ''){
    $periodId = Validator::ValidateRecievedId('period', 'POST');
    if(is_string($period))
        $error = 'Periodo inválido';
}

if($error === ''){
    include_once '../models/account_model.php';
    $account_model = new AccountModel();
    $target_account = $account_model->GetAccountByCedula($cedula);
    if($target_account === false)
        $error = 'Cuenta no encontrada';
}

if($error === ''){
    if($target_account['cedula'] !== $_SESSION[['neocaja_cedula']])
        $error = 'Acción denegada';
}


if($error === ''){
    include_once '../models/product_model.php';
    $product_model = new ProductModel();
    $prducts = $product_model->GetAvailableProductsOfStudent($cedula, $periodId);
    if($prducts === false)
        $error = 'Ocurrió un error al intentar obtener los productos disponibles';
}

if($error === ''){
    $response = [
        'status' => true,
        'data' => $prducts
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

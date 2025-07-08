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
    $account = Validator::ValidateRecievedId('account');
    if(is_string($account))
        $error = 'Id del cliente inválido';
}

if($error === ''){
    $period = Validator::ValidateRecievedId('period');
    if(is_string($period))
        $error = 'Id del periodo inválido';
}

if($error === ''){
    include_once '../models/account_model.php';
    $account_model = new AccountModel();
    $target_account = $account_model->GetAccount($account);
    if($target_account === false)
        $error = 'Cliente no encontrado';
}

if($error === ''){
    include_once '../models/siacad_model.php';
    $siacad = new SiacadModel();
    $target_period = $siacad->GetPeriodoById($period);
    if($target_account === false)
        $error = 'Periodo no encontrado';
}

if($error === ''){
    include_once '../models/invoice_model.php';
    $invoice_model = new InvoiceModel();
    $invoices = $invoice_model->GetInvoicesOfAccountOfPeriod($target_account['id'], $target_period['idperiodo']);
}

if($error === ''){
    $response = [
        'status' => true,
        'data' => $invoices
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

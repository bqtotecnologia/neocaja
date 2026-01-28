<?php
$admitted_user_types = ['Cajero', 'Super'];
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
    if(
        !isset($post['date']) || 
        !isset($post['ref'])
    ){
        $error = 'Campos necesarios no recibidos';
    }
}

if($error === ''){
    try {
        $target_date = new DateTime($post['date'], new DateTimeZone('America/Caracas'));
    } catch (\Throwable $th) {
        $error = 'Fecha inválida';
    }
}

if($error === ''){    
    include_once '../utils/Validator.php';
    $ref = Validator::HasSuspiciousCharacters($post['ref']);
    if($ref === true)
        $error = 'Referencia inválida';
    else
        $ref = $post['ref'];
}

if($error === ''){
    include_once '../models/unknown_incomes_model.php';
    $unknown_model = new UnknownIncomesModel();
    $incomes = $unknown_model->GetUnknownIncomesByDateAndReference($post['date'], $ref);
}

if($error === ''){
    $response = [
        'status' => true,
        'data' => $incomes
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
exit;
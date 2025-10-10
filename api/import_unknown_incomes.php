<?php
include_once '../utils/Auth.php';
$admitted_user_types = ['Cajero', 'Super'];
session_start();
$userOk = Auth::UserLevelIn($admitted_user_types);
$error = '';
if($userOk === false)
    $error = 'Permiso denegado. Cierre sesión e inicie nuevamente';

include_once '../utils/Validator.php';

$inputJSON = file_get_contents('php://input');
$post = json_decode($inputJSON, TRUE);


if($post === NULL){
    $error = 'POST vacío';
}

if($error === ''){
    if(!isset($post['payments'])){
        $error = 'Campos necesarios no recibidos';
    }
}

$response = ['status' => false, 'message' => $post['payments'][0]['description']];

if($error === ''){
    include_once '../models/unknown_incomes_model.php';
    $unknown_model = new UnknownIncomesModel();
    $target_generation = $unknown_model->CreateGeneration();
    if(is_string($target_generation))
        $error = $target_generation;
}

if($error === ''){
    for($i = 0; $i < count($post['payments']); $i++){
        $payment = $post['payments'][$i];

        if (!preg_match('/^\d{8}$/', $payment['date']))
            $error = 'Se recibió una fecha sin exactamente 8 caracteres: ' . $payment['date'];

        $day   = substr($payment['date'], 0, 2);
        $month   = substr($payment['date'], 2, 2);
        $year  = substr($payment['date'], 4, 4);

        // Validar que sea una fecha válida (por ejemplo, mes entre 1 y 12, día dentro del mes)
        if (!checkdate((int)$year, (int)$month, (int)$year))
            $error = 'Se recibió una fecha inválida: ' . $payment['date'];


        if($error === ''){
            $post['payment'][$i] = sprintf("%04d-%02d-%02d", $anio, $mes, $dia);
        }
        else{
            break;
        }
    }
}

if($error === ''){
    $inserted = $unknown_model->InsertUnknownIncomes($post['payments']);
}


$json_data = json_encode($response, JSON_UNESCAPED_UNICODE); // Para que acepte las tildes
header('Content-Type: application/json');
echo $json_data;
exit;

// BINNACLE

$json_data = json_encode($response, JSON_UNESCAPED_UNICODE); // Para que acepte las tildes
header('Content-Type: application/json');
echo $json_data;
exit;
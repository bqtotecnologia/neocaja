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
    if(!isset($post['payments'])){
        $error = 'Campos necesarios no recibidos';
    }
}

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

        $tentative_date = "$year-$month-$day";

        try {
            $date = new DateTime($tentative_date, new DateTimeZone('America/Caracas'));
        } catch (\Throwable $th) {
            $error = 'Se recibió una fecha inválida: ' . $payment['date'];
        }

        if($error === ''){
            $post['payments'][$i]['date'] = $tentative_date;
        }
        else{
            break;
        }
    }
}

if($error === ''){
    $inserted = $unknown_model->InsertUnknownIncomes($post['payments'], $target_generation['id']);
    $response = ['status' => false, 'message' => $error];

    if($inserted === false)
        $error = 'Ocurrió un error al intentar insertar los ingresos no identificados';
}


if($error === ''){
    $response = [
        'status' => true,
        'message' => 'Ingresos no identificados registrados correctamente'
    ];

    $action = 'Importó ' . count($post['payments']) . ' ingresos no identificados';
    $unknown_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
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
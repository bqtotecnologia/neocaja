<?php
$admitted_user_types = ['Estudiante', 'Super'];
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
        !isset($post['cedula']) || 
        !isset($post['period']) ||
        !isset($post['codes'])
    ){
        $error = 'Campos necesarios no recibidos';
    }
}

if($error === ''){
    include_once '../utils/Validator.php';
    $cedula = Validator::HasSuspiciousCharacters($post['cedula']);
    if($cedula === true)
        $error = 'Cédula inválida';
    else
        $cedula = $post['cedula'];
}

if($error === ''){    
    $period = Validator::HasSuspiciousCharacters($post['period']);
    if($period === true)
        $error = 'Periodo inválido';
    else
        $period = $post['period'];
}

if($error === ''){
    if(count($post['codes']) < 1){
        $error = 'No se seleccionaron productos';
    }else{
        foreach($post['codes'] as $code){
            $valid = Validator::HasSuspiciousCharacters($code);
            if($valid === true)
                $error = 'Texto malicioso detectado en algún producto';
        }
    }
}

if($error === ''){
    include_once '../models/account_model.php';
    $account_model = new AccountModel();
    $target_account = $account_model->GetAccountByCedula($cedula);
    if($target_account === false)
        $error = 'Cuenta no encontrada';
}

if($error === ''){
    if($target_account['cedula'] !== $_SESSION['neocaja_cedula'])
        $error = 'Acción denegada';
}

if($error === ''){
    include_once '../models/product_model.php';
    $product_model = new ProductModel();
    $products = $product_model->GetAvailableProductsOfStudent($cedula, $period);
    if($products === false)
        $error = 'Ocurrió un error al intentar obtener los productos disponibles';
}

if($error === ''){
    $result = [];
    foreach($products as $product){
        if(in_array($product['code'], $post['codes']))
            array_push($result, $product);
    }

    if(count($result) === 0)
        $error = 'No se encontraron los productos seleccionados';
}


if($error === ''){
    $response = [
        'status' => true,
        'data' => $result
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
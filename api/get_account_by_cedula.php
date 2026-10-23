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
    $cedula = Validator::ValidateRecievedId('cedula');
    if(is_string($cedula))
        $error = 'Cédula inválida';
}

if($error === ''){
    include_once '../models/account_model.php';
    $account_model = new AccountModel();

    $target_account = $account_model->GetAccountByCedula($cedula);
    $requireUpdateHistory = false;

    if($target_account === false){
        include_once '../models/siacad_model.php';
        $siacad = new SiacadModel();

        $student = $siacad->GetEstudianteByCedula($cedula);
        if($student === false){
            $error = 'Cédula no encontrada en el SIGEA';
        }
        else{
            /*
                Los telefonos en el sigea los registran así "telefono de casa" / "telefono personal"
                Los dividimos por / y tomamos el segundo valor
                El detalle es que no siempre lo escriben así, a veces escriben el numero tal cual a veces con un + o sin el 0
                Así que existe un gran margen de error.
            */
            $splits = explode('/', $student['telefonocontacto']);
            $splits = array_values(array_filter($splits, 'strlen'));
            $phone = isset($splits[1]) ? $splits[1] : $splits[0];
            $phone = trim($phone, ' ');
            
            $data = [
                'names' => $student['nombres'],
                'surnames' => $student['apellidos'],
                'cedula' => $cedula,
                'address' => $student['direccion'],
                'is_student' => 1,
                'phone' => $phone,
                'scholarship' => 'NULL',
                'scholarship_coverage' => 'NULL',
                'company' => 'NULL'
            ];
        
            $created = $account_model->CreateAccount($data);
            if($created === false)
                $error = 'Hubo un error al crear la cuenta del estudiante';
            else{
                $requireUpdateHistory = true;
                $target_account = $account_model->GetAccountByCedula($cedula);
            }
        }
    }
}

if($error === ''){
    if($requireUpdateHistory){
        $data = [
            'account' => $target_account['id'],
            'company' => NULL,
            'scholarship' => NULL,
            'scholarship_coverage' => NULL
        ];
        $updated = $account_model->UpdateAccountHistory($target_account['id'], $data);
        if($updated === false)
            $error = 'Hubo un error al intentar crear el historial empresa/beda del cliente';
    }
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

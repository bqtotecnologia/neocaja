<?php
include_once '../utils/base_url.php';
include_once '../utils/Validator.php';

$error = '';

if(empty($_POST))
    $error = 'POST vacío';


if($error === ''){
    include_once '../fields_config/login.php';
    $cleanData = Validator::ValidatePOSTFields($loginFields);
    if(is_string($cleanData))
        $error = $cleanData;
}

if($error === ''){
    $user = $cleanData['neocaja_user'];
    $password = sha1($cleanData['neocaja_password']);

    include_once '../models/bdusuarios_model.php';
    $bdusuarios = new BdusuariosModel();
    if(!$bdusuarios->UserExists($user))
        $error = 'Credenciales inválidas';
}

if($error === ''){
    include_once '../models/account_model.php';
    $account_model = new AccountModel();

    $target_user = $bdusuarios->TryLogin($user, $password);
    if($target_user === false){
        $error = 'Credenciales inválidas';
        $account_model->CreateBinnacle('NULL', 'Intento de inicio de sesión fallido para el usuario ' . $user);
    }
}

if($error === ''){
    // Login exitoso
    $names = explode(' ', $target_user['nombrecompleto']);
    $user_name = '';
    $user_surname = '';
    $cedula = $user;

    $user_name = strtoupper($names[0]);

    if(isset($names[2]))
        $user_surname = strtoupper($names[2]);
    else
        $user_surname = strtoupper($names[1]);

    include_once '../models/siacad_model.php';
    $siacad = new SiacadModel();
    
    if(isset($target_user['super_admin'])){
        // Es un super administrador
        $admin_id = '1';
        $user_role = 'Super';
    }
    else{
        $my_user = $siacad->GetUserTypeByCedula($cedula);
        if($my_user === [])
            $error = 'Su usuario no está registrado';
        else
            $user_role = $my_user['role'];
    }    
}

if($error === ''){
    if($user_role === 'Estudiante'){
        // Si es un estudiante, vamos a verificar que tenga cuenta, si no la tiene, la creamos
        include_once '../models/account_model.php';
        $account_model = new AccountModel();
    
        $account = $account_model->GetAccountByCedula($cedula);
        if($account === false){
            $student = $my_user['data'];

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
                $error = 'Hubo un error al crear tu cuenta';
        }
    }
}

if($error === ''){
    session_start();
    $_SESSION['neocaja_name'] = strtoupper($user_name);
    $_SESSION['neocaja_surname'] = strtoupper($user_surname);
    $_SESSION['neocaja_fullname'] = $_SESSION['neocaja_name'] . ' ' . $_SESSION['neocaja_surname'];
    $_SESSION['neocaja_rol'] = $user_role;
    $_SESSION['neocaja_cedula'] = $cedula;

    if($user_role !== 'Estudiante'){
        if(isset($my_user['data']['admin_id']))
            $admin_id = $my_user['data']['admin_id'];

        $_SESSION['neocaja_id'] = $admin_id;
    }
}

// Descomentar para verificar los datos 
/*
echo 'Nombre Completo: ' . $_SESSION['neocaja_fullname'] . '<br>';
echo 'Rol: ' . $_SESSION['neocaja_rol'] . '<br>';
echo 'Cédula: ' . $_SESSION['neocaja_cedula'] . '<br>';
echo 'Id: ' . $_SESSION['neocaja_id'] . '<br>';
echo "Error: " . $error;
exit;
*/


if($error === ''){
    include_once '../models/account_model.php';
    $account_model = new AccountModel();

    if($user_role === 'Estudiante')
        $account_model->CreateBinnacle('NULL', $_SESSION['neocaja_fullname'] . ' ha ingresado al sistema');
    else
        $account_model->CreateBinnacle($_SESSION['neocaja_id'], 'Ha ingresado al sistema');

    $allow_refresh = true;
    include_once 'refresh_coins.php';

    if($_SESSION['neocaja_rol'] !== 'Estudiante'){
        if($coins_refreshed){
            $redirect = "Location: $base_url/views/panel.php?";
            if($refresh_success)
                $redirect .= "message=$refresh_message";
            else
                $redirect .= "error=$refresh_message";
        }
        else
            $redirect = "Location: $base_url/views/panel.php";
    }
    else{
        $redirect = "Location: $base_url/views/panel.php";
    }
}
else
   $redirect = "Location: $base_url/views/forms/login.php?error=$error";

header($redirect);
exit;
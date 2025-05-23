<?php
include_once '../utils/base_url.php';
$error = '';
$redirect = 'Location: ' . $base_url . '/views/forms/login.php';

if(empty($_POST))
    $error = 'POST vacío';

if($error === ''){
    if(!isset($_POST['neocaja_user']) || !isset($_POST['neocaja_password']))
        $error = 'No se recibió ni un usuario ni una contraseña';
}

if($error === ''){    
    if($_POST['neocaja_user'] === '' || $_POST['neocaja_password'] === '')
        $error = 'Usuario o contraseña vacíos';
}

if($error !== ''){
    header($redirect . '?error=' . $error);
    exit;
}

if($error === ''){
    $user = $_POST['neocaja_user'];
    $password = sha1($_POST['neocaja_password']);
    
    // < > / \\ ; " ( ) { } [ ] $ & | ¿ ? ¡ ! = -   
    $regex = '/[<>\-\/;"\'(){}\[\]$\\\|&\?\¿\¡!=]/u';
    if(preg_match($regex, $user)){
        $error = 'El campo usuario contiene caracteres sospechosos';
    }
}

if($error === ''){
    include_once '../models/dbusuarios_model.php';
    $dbusuarios = new DbusuariosModel();
    if(!$dbusuarios->UserExists($user))
        $error = 'Credenciales inválidas';
}

if($error === ''){
    $target_user = $dbusuarios->TryLogin($user, $password);
    if($target_user === false)
        $error = 'Credenciales inválidas';
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
        $user_id = '-1';
        $user_role = 'Super';
    }
    else{
        $my_user = $siacad->GetUserTypeByCedula($cedula);
        $user_role = $my_user['role'];
    }

    if($user_role === 'Estudiante'){
        // Si es un estudiante, vamos a verificar que tenga cuenta, si no la tiene, la creamos
        include_once '../models/account_model.php';
        $account_model = new AccountModel();
    
        $account = $account_model->GetAccountByCedula($cedula);
        if($account === false){
            $student = $my_user['data'];
            
            $data = [
                'names' => $student['nombres'],
                'surnames' => $student['apellidos'],
                'cedula' => $cedula,
                'address' => $student['direccion'],
                'is_student' => 1,
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
    $_SESSION['neocaja_tipo'] = $user_role;
    $_SESSION['neocaja_cedula'] = $cedula;
}

// Descomentar para verificar los datos 

/*
var_dump($user_name); echo '<br>';
var_dump($user_surname); echo '<br>';
var_dump($cedula); echo '<br>';
var_dump($user_role); echo '<br>';
echo "Error: " . $error;
exit;
*/


if($error === '')
    header('Location: ' . $base_url . '/views/panel.php');
else
    header('Location: ' . $base_url . '/views/forms/login.php?error=' . $error);
exit;
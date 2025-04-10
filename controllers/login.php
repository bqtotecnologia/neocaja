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
    if(isset($usuario['super_admin'])){
        // Es un super administrador
        $user_id = '-1';
        $user_type = 'super';
    }
    else{
        $my_user = $siacad->GetUserTypeByCedula($cedula);
        $user_type = $my_user['type'];
    }
}

if($error === ''){
    session_start();
    $_SESSION['neocaja_name'] = strtoupper($user_name);
    $_SESSION['neocaja_surname'] = strtoupper($user_surname);
    $_SESSION['neocaja_tipo'] = $user_type;
    $_SESSION['neocaja_cedula'] = $cedula;
}

// Descomentar para verificar los datos 
/*
var_dump($user_name); echo '<br>';
var_dump($user_surname); echo '<br>';
var_dump($cedula); echo '<br>';
var_dump($user_type); echo '<br>';
var_dump($user_id); echo '<br>';
exit;
*/

if($error === ''){
    header('Location: ' . $base_url . '/views/panel.php');
}
else{
    header('Location: ' . $redirect . '?error=' . $error);
}

exit;
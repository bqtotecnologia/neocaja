<?php
include_once '../utils/base_url.php';
$error = '';
$redirect = 'Location: ' . $base_url . '/views/forms/login.php';

if(empty($_POST))
    $error = 'POST vacío';

if($error === ''){
    if(!isset($_POST['user']) || !isset($_POST['password']))
        $error = 'No se recibió ni un usuario ni una contraseña';
}

if($error === ''){    
    if($_POST['user'] === '' || $_POST['password'] === '')
        $error = 'Usuario o contraseña vacíos';
}

if($error !== ''){
    header($redirect . '?error=' . $error);
    exit;
}

if($error === ''){
    $user = $_POST['user'];
    $password = sha1($_POST['password']);
    
    // < > / \\ ; " ( ) { } [ ] $ & | ¿ ? ¡ ! = -   
    $regex = '/[<>\-\/;"\'(){}\[\]$\\\|&\?\¿\¡!=]/u';
    if(preg_match($regex, $user)){
        $error = 'El campo usuario contiene caracteres sospechosos';
    }
}

if($error === ''){
    include_once '../models/dbusuarios_model.php';
    $dbusuarios = new DbusuariosModel();
    if(!$dbusuarios->UserExists($user)){
        $error = 'Credenciales inválidas';
}


if($error === ''){
    $usuario = $dbusuarios->TryLogin($user, $password);
    if($usuario === false)
        $error = 'Credenciales inválidas';
}

if($error === ''){
// Usuario existe
}

}


else{
    
    if($usuario !== false){
        $names = explode(' ', $usuario['nombrecompleto']);
        $user_name = '';
        $user_surname = '';
        $cedula = $user;

        if(isset($names[1]))$user_name = strtoupper($names[0]);
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
            $my_user = $siacad->GetUserTypeByCedula($user);
            $user_type = $my_user['type'];

            if($my_user['type'] === 'teacher')
                $user_id = $my_user['data']['iddocente'];
            else if ($my_user['type'] === 'coord')
                $user_id = $my_user['iddocente'];
            else if ($my_user['type'] === 'student')
                $user_id = $my_user['data']['cedula'];
            else if ($my_user['type'] === 'admin')
                $user_id = $my_user['data']['cedula'];
        }

        $loginOK = false;
        if($user_type === 'student'){
            if($siacad->StudentIsActive($user)){
                // Si es un estudiante debemos verificar si puede evaluar
                $corte_to_vote = $siacad->GetCorteToVote();
                if($corte_to_vote === 'no permitido')
                    $redirect = 'Location: /evadoc/views/login.php?error=2';
                else{
                    /*
                    El estudiante está en un corte correcto
                    Debemos validar si le faltan docentes por evaluar en este corte
                    */
                    include_once '../models/docentes_periodos_model.php';
                    $docentes_periodos = new DocentesPeriodosModel();
                    $docentes_left = $docentes_periodos->EstudianteHaveRemainingDocentes($cedula);
                    if($docentes_left){
                        // Le quedan docentes por evaluar
                        $loginOK = true;
                        $redirect = 'Location: /evadoc/views/evaluar.php';
                    }
                    else{
                        // Ya evaluó a todos los docentes
                        $redirect = 'Location: /evadoc/views/login.php?error=3';
                    }
                }
            }
            else
                $redirect = 'Location: /evadoc/views/login.php?error=8';
        }
        else{
            // De ser un docente/admin/coordinador siempre podrá ingresar al sistema
            $loginOK = true;
            $redirect = 'Location: /evadoc/views/panel.php';
        }

        if($loginOK){
            session_start();
            $_SESSION['neocaja_user_id'] = $user_id;
            $_SESSION['neocaja_name'] = strtoupper($user_name);
            $_SESSION['neocaja_surname'] = strtoupper($user_surname);
            $_SESSION['neocaja_tipo'] = $user_type;
            $_SESSION['neocaja_cedula'] = $cedula;
        }
    }
    else{
        // El usuario no existe
        $redirect = 'Location: /evadoc/views/login.php?error=1';
    }
}

// Descomentar para verificar los datos 
/*
var_dump($user_name); echo '<br>';
var_dump($user_surname); echo '<br>';
var_dump($cedula); echo '<br>';
var_dump($user_type); echo '<br>';
var_dump($user_id); echo '<br>';
var_dump($redirect); echo '<br>';
exit;
*/

header($redirect);
exit;
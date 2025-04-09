<?php

session_start();
$error = '';
// Si el usuario NO es del tipo correcto, lo mandamos al index y cerramos la sesión
if($_SESSION['neocaja_tipo'] !== 'super'){
    session_destroy();
    header('Location:../index.php');
    exit;
}

if(empty($_POST)){
    $error = 'POST vacío';
}

if($error === ''){
    if(!isset($_POST['cedula']) || !isset($_POST['nivel']))
        $error = 'Información recibida errónea';
}

if($error === ''){
    if($_POST['cedula'] === '' || $_POST['nivel'] === '')
        $error = 'Información recibida vacía';
}

if($error === ''){
    if(is_numeric($_POST['cedula']) !== true)
        $error = 'La cédula debe ser de un valor numérico';
}
    
if($error === ''){
    if(strlen($_POST['cedula']) > 10 || strlen($_POST['cedula']) < 6)
        $error = 'La cédula debe tener entre 6 a 10 caracteres';
}

if($error === ''){
    // < > / \\ ; " { } [ ] $ & | ¿ ? ! = -   
    $regex = '/[<>\-\/;"\'{}\[\]$\\\|&\?\¿!=]/u';
    if (preg_match($regex, $_POST['cedula'])){
        // El texto puede ser malicioso
        $error = 'La cédula contiene caracteres sospechosos';
    }
}

if($error === '' && !in_array($_POST['nivel'], ['admin', 'coord'])){
    $error = 'Tipo de usuario inválido';
}


$edit = isset($_POST['edit']) ? $_POST['edit'] : false;
$delete = isset($_POST['delete']) ? $_POST['delete'] : false;
$target_id = null;

include_once '../models/admin_model.php';
$admin_model = new AdminModel();
$admin_exists = null;    

if($error === '' && ($edit === '1' || $delete === '1')){
    $admin_exists = $admin_model->GetAdminById($_POST['id']);
    if($admin_exists === false)
        $error = 'Administrador no encontrado';
    else
        $target_id = $admin_exists['id'];
}

if($error === '' && $edit !== '1' && $delete !== '1'){
    $admin_exists = $admin_model->GetAdminByCedula($_POST['cedula']);
    if($admin_exists !== false)
        $error = 'Cédula repetida';
}



if($error === ''){
    $result = null;
    if($edit === '1'){
        // Editamos al admin
        $result = $admin_model->UpdateAdmin($target_id, $_POST['cedula'], $_POST['nivel']);
        if($result === true){
            header('Location: ../views/search_admin.php?message=Admin actualizado correctamente');
            exit;
        }
    }

    if($delete === '1'){
        // Eliminamos el admin
        $result = $admin_model->DeleteAdmin($target_id);
        if($result === true){
            header('Location: ../views/search_admin.php?message=Admin eliminado correctamente');
            exit;
        }
    }

    if($delete === false && $edit === false){
        // Agregamos al admin
        $result = $admin_model->AddAdmin($_POST['cedula'], $_POST['nivel']);
        if($result === true){
            header('Location: ../views/search_admin.php?message=Admin agregado correctamente');
            exit;
        }
    }
    if($result === null)
        header("Location: ../views/add_admin.php?edit=1&id=$target_id&error=Ocurrió un error extraño");
    else if($result !== true)
        header("Location: ../views/add_admin.php?edit=1&id=$target_id&error=$result");
    else
        header('Location: ../views/search_admin.php?error=Ocurrió un error');
    exit;
}
else{
    // Hubo algún error
    if(isset($_POST['id']))
        header("Location: ../views/add_admin.php?error=$error&id=" . $_POST['id'] . "&edit=1");
    else 
        header("Location: ../views/add_admin.php?error=$error");
    exit;
}
<?php
session_start();
$error = '';
// Si el usuario NO es del tipo correcto, lo mandamos al index y cerramos la sesión
if(!in_array($_SESSION['neocaja_tipo'], ['super', 'admin'])){
    session_destroy();
    header('Location:../index.php');
    exit;
}

if(empty($_POST)){
    $error = 'POST vacío';
}

if($error === ''){
    if(!isset($_POST['element_name']))
        $error = 'Información recibida errónea';
}

if($error === ''){
    if($_POST['element_name'] === '')
    $error = 'Información recibida vacía';
}
    
if($error === ''){
    if(strlen($_POST['element_name']) >= 300)
        $error = 'El nombre del grupo criterio excede el límite de caracteres (300)';
}

if($error === ''){
    // < > / \\ ; " { } [ ] $ & | ¿ ? ! = -   
    $regex = '/[<>\/;"\'{}\[\]$\\\|&\?\¿!=]/u';
    if (preg_match($regex, $_POST['element_name']))
        $error = 'El nombre del grupo criterio contiene caracteres sospechosos';
}

$edit = isset($_POST['edit']) ? $_POST['edit'] : false;
include_once '../models/criterio_model.php';
$criterio_model = new CriterioModel();
$update_id = null;

if($error === '' && $edit === '1'){
    if(!isset($_POST['element_id']))
        $error = 'Id a modificar no existente';
    else{
        $update_id = $_POST['element_id'];
        $grupo_criterio_to_update = $criterio_model->GetGrupoCriterioById($update_id);
        if($grupo_criterio_to_update === false)
            $error = 'Grupo criterio a modificar no encontrada';        
    }

}

if($error === ''){
    if($edit === false){
        // Agregamos la Grupo criterio
        $result = $criterio_model->AddGrupoCriterio($_POST['element_name']);
        if($result === true)
            header('Location: ../views/evaluation_element_list.php?message=Grupo criterio agregado&element=grupo_criterio');
        else
            header("Location: ../views/add_grupo_criterio.php?error=$result");
        exit;
    }
    else{
        // Actualizamos la Grupo criterio
        $result = $criterio_model->UpdateGrupoCriterio($update_id, $_POST['element_name']);
        if($result === true)
            header('Location: ../views/evaluation_element_list.php?message=Grupo criterio modificado&element=grupo_criterio');
        else
            header("Location: ../views/add_grupo_criterio.php?edit=1&id=$update_id&error=$result");
        exit;
    }
}
else{
    // Hubo algún error
    header("Location: ../views/add_grupo_criterio.php?error=$error");
}
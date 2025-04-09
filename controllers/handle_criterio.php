<?php
session_start();
$error = '';
// Si el usuario NO es del tipo correcto, lo mandamos al index y cerramos la sesión
if(!in_array($_SESSION['eva_tipo'], ['super', 'admin'])){
    session_destroy();
    header('Location:../index.php');
    exit;
}

if(empty($_POST)){
    $error = 'POST vacío';
}

if($error === ''){
    if(!isset($_POST['element_name']) || !isset($_POST['grupo_criterio']) || !isset($_POST['peso']))
        $error = 'Información recibida errónea';
}

if($error === ''){
    if($_POST['element_name'] === '' || $_POST['grupo_criterio'] === '' || $_POST['peso'] === '')
    $error = 'Información recibida vacía';
}
    
if($error === ''){
    if(strlen($_POST['element_name']) >= 300)
        $error = 'El nombre del criterio excede el límite de caracteres (300)';
}

if($error === ''){
    // < > / \\ ; " { } [ ] $ & | ¿ ? ! = -   
    $regex = '/[<>\-\/;"\'(){}\[\]$\\\|&\?\¿\¡!=]/u';
    if (preg_match($regex, $_POST['element_name'])){
        // El texto puede ser malicioso
        $error = 'El nombre del criterio contiene caracteres sospechosos';
    }
}

$weight = intval($_POST['peso']);

include_once '../models/criterio_model.php';
$criterio_model = new CriterioModel();
$target_grupo_criterio = $criterio_model->GetGrupoCriterioById($_POST['grupo_criterio']);

if($error === ''){
    if($target_grupo_criterio === false)
        $error = 'Tipo criterio no encontrado no encontrada';
}


$edit = isset($_POST['edit']) ? $_POST['edit'] : false;
$update_id = null;

if($error === '' && $edit === '1'){
    if(!isset($_POST['element_id']))
        $error = 'Id a modificar no existente';
    else{
        $update_id = $_POST['element_id'];
        $criterio_to_update = $criterio_model->GetCriterioById($update_id);
        if($criterio_to_update === false)
            $error = 'Criterio a modificar no encontrado';        
    }
}

if($error === ''){
    $data = [
        'name' => $_POST['element_name'],
        'weight' => $weight,
        'grupo_criterio' => $target_grupo_criterio['id']
    ];
    if($edit === false){
        // Agregamos el criterio
        $result = $criterio_model->AddCriterio($data);
        if($result === true)
            header('Location: ../views/evaluation_element_list.php?message=Criterio agregado&element=criterio');
        else
            header("Location: ../views/add_criterio.php?error=$result");
        exit;
    }
    else{
        // Actualizamos el criterio
        $result = $criterio_model->UpdateCriterio($data, $update_id);
        if($result === true)
            header('Location: ../views/evaluation_element_list.php?message=Criterio modificado&element=criterio');
        else
            header("Location: ../views/add_criterio.php?edit=1&id=$update_id&error=$result");
        exit;
    }
}
else{
    // Hubo algún error
    header("Location: ../views/add_criterio.php?error=$error");
    exit;
}
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
    if(!isset($_POST['element_name']) || !isset($_POST['dimension']) || !isset($_POST['type']))
        $error = 'Información recibida errónea';
}

if($error === ''){
    if($_POST['element_name'] === '' || $_POST['dimension'] === '' || $_POST['type'] === '')
    $error = 'Información recibida vacía';
}
    
if($error === ''){
    if(strlen($_POST['element_name']) >= 300)
        $error = 'El nombre del indicador excede el límite de caracteres (300)';
}

if($error === ''){
    // < > / \\ ; " { } [ ] $ & | ¿ ? ! = -   
    $regex = '/[<>\-\/;"\'(){}\[\]$\\\|&\?\¿\¡!=]/u';
    if (preg_match($regex, $_POST['element_name'])){
        // El texto puede ser malicioso
        $error = 'El nombre del indicador contiene caracteres sospechosos';
    }
}

if($error === ''){
    if(!in_array($_POST['type'], ['teacher', 'student', 'coord']))
        $error = "El tipo es incorrecto";
}

include_once '../models/criterio_model.php';
$criterio_model = new CriterioModel();
$target_dimension = $criterio_model->GetDimensionById($_POST['dimension']);

if($error === ''){
    if($target_dimension === false)
        $error = 'Dimensión no encontrada';
}


$edit = isset($_POST['edit']) ? $_POST['edit'] : false;
$update_id = null;

if($error === '' && $edit === '1'){
    if(!isset($_POST['element_id']))
        $error = 'Id a modificar no existente';
    else{
        $update_id = $_POST['element_id'];
        $indicador_to_update = $criterio_model->GetIndicadorById($update_id);
        if($indicador_to_update === false)
            $error = 'Indicador a modificar no encontrada';        
    }
}

if($error === ''){
    if($edit === false){
        // Agregamos el indicador
        $result = $criterio_model->AddIndicador($_POST['element_name'], $target_dimension['id_dimension'], $_POST['type']);
        if($result === true)
            header('Location: ../views/evaluation_element_list.php?message=Indicador agregado&element=indicador');
        else
            header("Location: ../views/add_indicador.php?error=$result");
        exit;
    }
    else{
        // Actualizamos el indicador
        $result = $criterio_model->UpdateIndicador($update_id, $_POST['element_name'], $target_dimension['id_dimension'], $_POST['type']);
        if($result === true)
            header('Location: ../views/evaluation_element_list.php?message=Indicador modificado&element=indicador');
        else
            header("Location: ../views/add_indicador.php?edit=1&id=$update_id&error=$result");
        exit;
    }
}
else{
    // Hubo algún error
    header("Location: ../views/add_indicador.php?error=$error");
    exit;
}
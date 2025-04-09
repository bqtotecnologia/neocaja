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
    if(!isset($_POST['element_name']) || !isset($_POST['categoria']))
        $error = 'Información recibida errónea';
}

if($error === ''){
    if($_POST['element_name'] === '' || $_POST['categoria'] === '')
    $error = 'Información recibida vacía';
}
    
if($error === ''){
    if(strlen($_POST['element_name']) >= 300)
        $error = 'El nombre de la dimensión excede el límite de caracteres (300)';
}

if($error === ''){
    // < > / \\ ; " { } [ ] $ & | ¿ ? ! = -   
    $regex = '/[<>\-\/;"\'{}\[\]$\\\|&\?\¿!=]/u';
    if (preg_match($regex, $_POST['element_name'])){
        // El texto puede ser malicioso
        $error = 'El nombre de la dimensión contiene caracteres sospechosos';
    }
}

include_once '../models/criterio_model.php';
$criterio_model = new CriterioModel();
$target_categoria = $criterio_model->GetCategoriaById($_POST['categoria']);

if($error === ''){
    if($target_categoria === false)
        $error = 'Categoría no encontrada';
}


$edit = isset($_POST['edit']) ? $_POST['edit'] : false;
$update_id = null;

if($error === '' && $edit === '1'){
    if(!isset($_POST['element_id']))
        $error = 'Id a modificar no existente';
    else{
        $update_id = $_POST['element_id'];
        $dimension_to_update = $criterio_model->GetDimensionById($update_id);
        if($dimension_to_update === false)
            $error = 'Categoría a modificar no encontrada';        
    }
}

if($error === ''){
    if($edit === false){
        // Agregamos la Dimension
        $result = $criterio_model->AddDimension($_POST['element_name'], $target_categoria['id']);
        if($result === true)
            header('Location: ../views/evaluation_element_list.php?message=Dimensión agregada&element=dimension');
        else
            header("Location: ../views/add_dimension.php?error=$result");
        exit;
    }
    else{
        // Actualizamos la Dimension
        $result = $criterio_model->UpdateDimension($update_id, $_POST['element_name'], $target_categoria['id']);
        if($result === true)
            header('Location: ../views/evaluation_element_list.php?message=Dimensión modificada&element=dimension');
        else
            header("Location: ../views/add_dimension.php?edit=1&id=$update_id&error=$result");
        exit;
    }
}
else{
    // Hubo algún error
    header("Location: ../views/add_dimension.php?error=$error");
    exit;
}
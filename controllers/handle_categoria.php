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
        $error = 'El nombre de la categoría excede el límite de caracteres (300)';
}

if($error === ''){
    // < > / \\ ; " { } [ ] $ & | ¿ ? ! = -   
    $regex = '/[<>\-\/;"\'{}\[\]$\\\|&\?\¿!=]/u';
    if (preg_match($regex, $_POST['element_name']))
        $error = 'El nombre de la categoría contiene caracteres sospechosos';
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
        $categoria_to_update = $criterio_model->GetCategoriaById($update_id);
        if($categoria_to_update === false)
            $error = 'Categoría a modificar no encontrada';        
    }

}

if($error === ''){
    if($edit === false){
        // Agregamos la categoria
        $result = $criterio_model->AddCategoria($_POST['element_name']);
        if($result === true)
            header('Location: ../views/evaluation_element_list.php?message=Categoría agregada&element=categoria');
        else
            header("Location: ../views/add_categoria.php?error=$result");
        exit;
    }
    else{
        // Actualizamos la categoria
        $result = $criterio_model->UpdateCategoria($update_id, $_POST['element_name']);
        if($result === true)
            header('Location: ../views/evaluation_element_list.php?message=Categoría modificada&element=categoria');
        else
            header("Location: ../views/add_categoria.php?edit=1&id=$update_id&error=$result");
        exit;
    }
}
else{
    // Hubo algún error
    header("Location: ../views/add_categoria.php?error=3");
}
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
    if(
        !isset($_POST['element_name']) || 
        !isset($_POST['indicador']) || 
        !isset($_POST['grupo_criterio'])
        )
        $error = 'Información recibida errónea';
}
if($error === ''){
    if($_POST['element_name'] === '' || $_POST['indicador'] === '' || $_POST['grupo_criterio'] === ''){
        $error = 'Información recibida vacía';
    }
}
    
if($error === ''){
    if(strlen($_POST['element_name']) >= 300)
        $error = 'El nombre del enunciado excede el límite de caracteres (300)';
}

if($error === ''){
    // < > / \\ ; " { } [ ] $ & | ¿ ? ! = -   
    $regex = '/[<>\-\/;"\'{}\[\]$\\\|&\?\¿!=]/u';
    if (preg_match($regex, $_POST['element_name'])){
        // El texto puede ser malicioso
        $error = 'El nombre del enunciado contiene caracteres sospechosos';
    }
}

if($error === ''){
    $corte = $_POST['corte'] === '' ? "''" : $_POST['corte'];
    $active = isset($_POST['active']) ? 'true' : 'false';
    $multi_select = isset($_POST['multi-select']) ? 'true' : 'false';
    
    if($error === '' && $corte !== "''" && !in_array($corte, ['I', 'II', 'III'])){
        $error = 'Corte inválido';
    }
    
    include_once '../models/criterio_model.php';
    $criterio_model = new CriterioModel();
    $target_indicador = $criterio_model->GetIndicadorById($_POST['indicador']);
    $target_grupo_criterio = $criterio_model->GetGrupoCriterioById($_POST['grupo_criterio']);
}

if($error === ''){
    if($target_indicador === false)
        $error = 'Indicador no encontrado';
}


$edit = isset($_POST['edit']) ? $_POST['edit'] : false;
$update_id = null;

if($error === '' && $edit === '1'){
    if(!isset($_POST['element_id']))
        $error = 'Id a modificar no existente';
    else{
        $update_id = $_POST['element_id'];
        $enunciado_to_update = $criterio_model->GetEnunciadoById($update_id);
        if($enunciado_to_update === false)
            $error = 'Enunciado a modificar no encontrada';        
    }
}

if($error === ''){
    $data = [
        'nombre' => $_POST['element_name'],
        'indicador' => $target_indicador['id_indicador'],
        'corte' => $corte,
        'active' => $active,
        'multi_select' => $multi_select,
        'grupo_criterio' => $target_grupo_criterio['id']
    ];
    if($edit === false){
        // Agregamos el Enunciado
        $result = $criterio_model->AddEnunciado($data);
        if($result === true)
            header('Location: ../views/evaluation_element_list.php?message=Enunciado agregado&element=enunciado');
        else
            header("Location: ../views/add_enunciado.php?error=$result");
        exit;
    }
    else{
        // Actualizamos el Enunciado
        $data['id'] = $enunciado_to_update['id'];
        $result = $criterio_model->UpdateEnunciado($data);
        if($result === true)
            header('Location: ../views/evaluation_element_list.php?message=Enunciado modificado&element=enunciado');
        else
            header("Location: ../views/add_enunciado.php?edit=1&id=$update_id&error=$result");
        exit;
    }
}
else{
    // Hubo algún error
    header("Location: ../views/add_enunciado.php?error=$error");
    exit;
}
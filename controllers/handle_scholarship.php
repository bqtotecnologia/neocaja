<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';

include_once '../utils/base_url.php';
include_once '../utils/Validator.php';

$error = '';
$target_scholarship = false;

if(empty($_POST)){
    $error = 'POST vacío';
}

$fields_config = [
    'name' => [
        'min' => 1,
        'max' => 100,
        'required' => true,
        'type' => 'string',
        'suspicious' => true,
    ],
];

$result = Validator::ValidatePOSTFields($fields_config);
if(is_string($result))
    $error = $result;
else
    $cleanData = $result;

$edit = isset($_POST['id']);

if($error === ''){
    include_once '../models/scholarship_model.php';
    $scholarship_model = new ScholarshipModel();

    if($edit){
        $target_scholarship = $scholarship_model->GetScholarship($cleanData['id']);
        if($target_scholarship === false)
            $error = 'Beca no encontrada';
    }
    else{
        $target_scholarship = $scholarship_model->GetScholarshipByName($cleanData['name']);
        if($target_scholarship !== false)
            $error = 'El nombre de la beca está repetido';
    }   
}

if($error === ''){
    if($edit){
        if($target_scholarship['id'] !== $cleanData['id'] && $target_account['name'] === $cleanData['name'])
            $error = 'El nombre ingresado ya está registrado';
    }
}  

// Creating / updating the account
if($error === ''){    
    if($edit){
        $updated = $scholarship_model->UpdateScholarship($cleanData['id'], $cleanData);
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar la beca';
    }
    else{
        $created = $scholarship_model->CreateScholarship($cleanData);
        if($created === false)
            $error = 'Hubo un error al intentar registrar la beca';
    }
}

// Managing feedback message and binnacle
if($error === ''){
    if($edit){        
        $message = 'Beca actualizada correctamente';

        $nameChanged = $cleanData['name'] !== $target_scholarship['name'];

        $action = 'Actualizó la beca ' . $target_scholarship['name'];

        if($nameChanged)
            $action .= '. Al nombre ' . $cleanData['name'];
    }
    else{
        $message = 'Beca registrada correctamente';
        $action = 'Creo la beca ' . $cleanData['name'];
    }
    $scholarship_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){    
    if($edit)
        header("Location: $base_url/views/forms/scholarship_form.php?message=$message&id=" . $cleanData['id']);
    else
        header("Location: $base_url/views/forms/scholarship_form.php?message=$message&id=" . $created['id']);
}
else{
    if($edit){
        if($target_scholarship === false)
            header("Location: $base_url/views/tables/search_scholarship.php?error=$error");
        else
            header("Location: $base_url/views/forms/scholarship_form.php?error=$error&id=" . $target_scholarship['id']);
    }
    else
        header("Location: $base_url/views/forms/scholarship_form.php?error=$error");
}

exit;
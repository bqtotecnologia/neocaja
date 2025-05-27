<?php
$admitted_user_types = ['Super'];
include_once '../utils/validate_user_type.php';

include_once '../utils/base_url.php';
include_once '../utils/Validator.php';

$validator = new Validator();

$error = '';
if(empty($_POST)){
    $error = 'POST vacío';
}

$fields_config = [
    'cedula' => [
        'min' => 7,
        'max' => 11,
        'required' => true,
        'type' => 'string',
        'suspicious' => true,
    ],
    'name' => [
        'min' => 5,
        'max' => 50,
        'required' => true,
        'type' => 'string',
        'suspicious' => true,
    ],
    'role' => [
        'min' => 1,
        'max' => 11,
        'required' => true,
        'type' => 'numberic',
        'suspicious' => true,
    ],
];

$result = $validator->ValidatePOSTFields($fields_config);
if(is_string($result))
    $error = $result;
else
    $cleanData = $result;

$edit = isset($cleanData['id']);

if($error === ''){
    include_once '../models/admin_model.php';
    $admin_model = new AdminModel();

    if($edit){
        $target_admin = $admin_model->GetAdminById($cleanData['id']);
        if($target_admin === false)
            $error = 'Admin no encontrado';
    }
    else{
        $target_admin = $admin_model->GetAdminByCedula($cleanData['cedula']);
        if($target_admin !== false)
            $error = 'La cédula ya está registrada';
    }   
}

if($error === ''){
    if($edit){
        $same_cedula = $admin_model->GetAdminByCedula($cleanData['cedula']);
        if($same_cedula['cedula'] === $target_admin['cedula'] && intval($target_admin['admin_id']) !== $cleanData['id'])
            $error = 'La cédula ya está registrada';
    }    
}    

if($error === ''){
    $target_role = $admin_model->GetRoleById($cleanData['role']);
    if($target_role === false)
        $error = 'Rol no encontrado';
}

// Creating the admin
if($error === ''){
    if($edit){
        $updated = $admin_model->UpdateAdmin($cleanData['id'], $cleanData);
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar el admin';
        else{
            $message = 'Admin modificado exitosamente';            
            $action = 'Modificó al admin ' . $target_admin['name'];
            $admin_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
        }
    }
    else{
        $created = $admin_model->CreateAdmin($cleanData);
        if($created === false)
            $error = 'Hubo un error al intentar registrar el admin';
        else{
            $message = 'Admin creado exitosamente';
            $action = 'Creó al admin ' . $cleanData['name'];
            $admin_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
        }
    }
}

if($error !== ''){
    if($edit){
        header("Location: $base_url/views/forms/admin_form.php?error=$error&id=" . $cleanData['id']);
    }
    else{
        header("Location: $base_url/views/forms/admin_form.php?error=$error");
    }
    exit;
}
else{
    header("Location: $base_url/views/tables/search_admin.php?message=$message&id=" . $created['admin_id']);
    exit;
}

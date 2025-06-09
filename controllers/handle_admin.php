<?php
$admitted_user_types = ['Super'];
include_once '../utils/validate_user_type.php';

include_once '../utils/base_url.php';
include_once '../utils/Validator.php';

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
        'type' => 'integer',
        'suspicious' => true,
    ],
];

$result = Validator::ValidatePOSTFields($fields_config);
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
    $cleanData['active'] = isset($_POST['active']) ? '1' : '0';

    if($edit){
        $nameChanged = false;
        $activeChanged = false;
        $roleChanged = false;

        $updated = $admin_model->UpdateAdmin($cleanData['id'], $cleanData);
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar el admin';
    }
    else{
        $created = $admin_model->CreateAdmin($cleanData);
        if($created === false)
            $error = 'Hubo un error al intentar registrar el admin';
    }
}

// Managing feedback message and binnacle
if($error === ''){
    if($edit){
        $message = 'Admin modificado exitosamente';  
        $action = 'Modificó al admin ' . $target_admin['name'];
        

        $nameChanged = $cleanData['name'] !== $target_admin['name'];
        $activeChanged = $cleanData['active'] !== $target_admin['active'];
        $roleChanged = $cleanData['role'] !== $target_admin['role_id'];
        $cedulaChanged = $cleanData['cedula'] !== $target_admin['cedula'];
        
        if($nameChanged)
            $action .= '. Al nombre ' . $cleanData['name'];

        if($activeChanged)
            $action .= '. Al estado activo ' . ($cleanData['active'] === '1' ? 'Si' : 'No');

        if($priceChanged)
            $action .= '. Al rol ' . $target_role['name'];

        if($cedulaChanged)
            $action .= '. A la cédula ' . $cleanData['cedula'];
    }
    else{
        $message = 'Admin creado exitosamente';
        $action = 'Creo al admin ' . $cleanData['name'] . ' cedula ' . $cleanData['cedula'] . ' rol ' . $target_role['name'] . ' activo ' . ($cleanData['active'] === '1' ? 'Si' : 'No');
    }
    
    $admin_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){    
    if($edit)
        header("Location: $base_url/views/forms/admin_form.php?message=$message&id=" . $cleanData['id']);
    else
        header("Location: $base_url/views/forms/admin_form.php?message=$message&id=" . $created['id']);
}
else{
    if($edit){
        if($target_product === false)
            header("Location: $base_url/views/tables/search_admin.php?error=$error");
        else
            header("Location: $base_url/views/forms/admin_form.php?error=$error&id=" . $target_product['id']);
    }
    else
        header("Location: $base_url/views/forms/admin_form.php?error=$error");
}

exit;

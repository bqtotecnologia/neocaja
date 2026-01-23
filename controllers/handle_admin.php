<?php
$admitted_user_types = ['Super'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';

$error = '';
$form = false;
if(empty($_POST)){
    $error = 'POST vacío';
}

$edit = isset($_POST['id']);
if($error === '' && $edit){
    $id = Validator::ValidateRecievedId('id', 'POST');
    if(is_string($id))
        $error = $id;
}

if($error === ''){
    include_once '../models/admin_model.php';
    $admin_model = new AdminModel();

    if($edit){
        $target_admin = $admin_model->GetAdminById($id);
        if($target_admin === false)
            $error = 'Admin no encontrado';
    }
}

if($error === ''){
    include_once '../fields_config/admins.php';
    $cleanData = Validator::ValidatePOSTFields($adminFields);
    if(is_string($cleanData))
        $error = $cleanData;
}

if($error === ''){
    $exists = $admin_model->GetAdminByCedula($cleanData['cedula']);
    if($exists !== false){
        if($edit){
            if(intval($exists['id']) !== intval($id))
                $error = 'La cédula ya está registrada';
        }    
        else
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

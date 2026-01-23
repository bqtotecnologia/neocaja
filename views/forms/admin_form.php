<?php
$admitted_user_types = ['Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/Validator.php';

include_once '../../models/admin_model.php';
$admin_model = new AdminModel();

$edit = isset($_GET['id']);
$form = true;
$error = '';
if($edit){
    $id = Validator::ValidateRecievedId();
    if(is_string($id))
        $error = $id;    
}

if($error === '' && $edit){
    $target_admin = $admin_model->GetAdminById($id);
    if($target_admin === false){
        $error = 'Usuario no encontrado';
    }
    else{
        if($target_admin['role'] === 'Super')
            $error = 'El super adminsitrador no puede ser modificado';
    }
}

if($error !== ''){
    header("Location: $base_url/views/panel.php?error=$error");
    exit;
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';


$roles = $admin_model->GetRoles();
$display_roles = [];
foreach($roles as $role){
    array_push($display_roles, ['display' => $role['name'], 'value' => $role['id']]);
}

include_once '../../fields_config/admins.php';
$formBuilder = new FormBuilder(
    '../../controllers/handle_admin.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nuevo') . ' admin',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $adminFields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_admin.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>
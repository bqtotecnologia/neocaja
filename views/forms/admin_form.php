<?php
$admitted_user_types = ['Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

include_once '../../models/admin_model.php';
$admin_model = new AdminModel();

$edit = isset($_GET['id']);
if($edit){
    if(!is_numeric($_GET['id'])){
        header("Location: $base_url/views/tables/search_admin.php?error=Id inválido");
        exit;
    }   

    $target_admin = $admin_model->GetAdminById($_GET['id']);
    if($target_admin === false){
        header("Location: $base_url/views/tables/search_admin.php?error=Usuario no encontrado");
        exit;
    }
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';

$roles = $admin_model->GetRoles();
$display_roles = [];
foreach($roles as $role){
    array_push($display_roles, ['display' => $role['name'], 'value' => $role['id']]);
}

$fields = [
    [
        'name' => 'cedula',
        'display' => 'Cedula',
        'placeholder' => 'Cédula del administrador',
        'id' => 'cedula',
        'type' => 'text',
        'size' => 5,
        'max' => 11,
        'min' => 7,
        'required' => true,
        'value' => $edit ? $target_admin['cedula'] : ''
    ],
    [
        'name' => 'name',
        'display' => 'Nombre',
        'placeholder' => 'Nombre del administrador',
        'id' => 'name',
        'type' => 'text',
        'size' => 8,
        'max' => 50,
        'min' => 5,
        'required' => true,
        'value' => $edit ? $target_admin['name'] : ''
    ],
    [
        'name' => 'role',
        'display' => 'Rol',
        'placeholder' => '',
        'id' => 'role',
        'type' => 'select',
        'size' => 3,
        'required' => true,
        'value' => $edit ? $target_admin['role_id'] : '',
        'elements' => $display_roles
    ],
    [
        'name' => 'active',
        'display' => 'Activo',
        'placeholder' => '',
        'id' => 'active',
        'type' => 'checkbox',
        'size' => 4,
        'required' => false,
        'value' => $edit ? [$target_admin['active']] : ['1'],
        'elements' => [
            [
                'display' => 'Activo',
                'value' => '1'
            ]
        ]
    ],
];

if($edit){
    $id_field = [
        'name' => 'id',
        'value' => $target_admin['admin_id']
    ];
    array_push($fields, $id_field);
}

$formBuilder = new FormBuilder(
    '../../controllers/handle_admin.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nuevo') . ' admin',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $fields
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
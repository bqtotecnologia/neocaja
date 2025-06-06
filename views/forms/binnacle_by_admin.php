<?php
$admitted_user_types = ['Super', 'Supervisor'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

include_once '../../models/admin_model.php';
$admin_model = new AdminModel();

if(!empty($_POST)){
    $error = '';
    include_once '../../utils/Validator.php';
    $id = Validator::ValidateRecievedId('admin', 'POST');
    if(is_string($id))
        $error = $id;

    if($error === ''){        
        $target_admin = $admin_model->GetAdminById($id);
        if($target_admin === false)
            $error = 'Admin no encontrado';
    }

    if($error !== ''){
        header("Location: $base_url/views/forms/binnacle_by_user.php?error=$error");
        exit;
    }
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';

$admins = $admin_model->GetAdmins();
$display_admins = [];
foreach($admins as $admin){
    $to_add = [
        'value' => $admin['admin_id'],
        'display' => $admin['name']
    ];

    array_push($display_admins, $to_add);
}

$fields = [
    [
        'name' => 'admin',
        'display' => 'Usuario',
        'id' => 'admin',
        'type' => 'select',
        'placeholder' => '',
        'size' => 8,
        'required' => true,
        'value' => empty($_POST) ? '' : $_POST['admin'],
        'elements' => $display_admins
    ],
];

$formBuilder = new FormBuilder(
    '',    
    'POST',
    'Buscar bitácora por usuario',
    'Buscar',
    '',
    $fields,
    false
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../panel.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>


    <?php if(!empty($_POST)) { ?>
        <?php 
            include_once '../../models/binnacle_model.php';
            $binnacle_model = new BinnacleModel();
            $binnacle = $binnacle_model->GetBinnacleOfUser($id);
        ?>
        <div class="col-12 row justify-content-center px-4">
            <div class="col-12 row justify-content-center x_panel">
                <?php 
                include '../common/tables/binnacle_table.php'; 
                ?>
            </div>
        </div>
    <?php } ?>
</div>

<?php include_once '../common/footer.php'; ?>
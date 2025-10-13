<?php
$admitted_user_types = ['Super', 'Cajero'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

$edit = isset($_GET['id']);
if($edit){
    if(!is_numeric($_GET['id'])){
        header("Location: $base_url/views/tables/search_global_vars.php?error=Id inválido");
        exit;
    }   

    include_once '../../models/global_vars_model.php';
    $bank_model = new GlobalVarsModel();
    $target_global_var = $bank_model->GetglobalVar($_GET['id']);
    if($target_global_var === false){
        header("Location: $base_url/views/tables/search_global_vars.php?error=Variable global no encontrada");
        exit;
    }
}
else{
    header("Location: $base_url/views/panel.php");
    exit;
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';

$fields = [
    [
        'name' => 'name',
        'display' => 'Nombre',
        'placeholder' => 'Nombre',
        'id' => 'name',
        'type' => 'text',
        'size' => 8,
        'max' => 255,
        'min' => 5,
        'required' => true,
        'disabled' => true,
        'value' => $target_global_var['name']
    ],
    [
        'name' => 'value',
        'display' => 'Valor',
        'placeholder' => 'Valor',
        'id' => 'value',
        'type' => 'decimal',
        'size' => 4,
        'max' => 4,
        'min' => 4,
        'required' => true,
        'value' => $target_global_var['value']
    ],
];

if($edit){
    $id_field = [
        'name' => 'id',
        'value' => $target_global_var['id']
    ];
    array_push($fields, $id_field);
}

$formBuilder = new FormBuilder(
    '../../controllers/update_global_var.php',    
    'POST',
    'Actualizar variable global',
    'Actualizar',
    '',
    $fields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_global_var.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>
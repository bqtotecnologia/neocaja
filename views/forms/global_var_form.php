<?php
$admitted_user_types = ['Super', 'Cajero'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/Validator.php';

$error = '';
if(empty($_GET))
    $error = 'GET vacío';

if($error === ''){
    $id = Validator::ValidateRecievedId();
    if(is_string($id))
        $error = $id;
}

if($error === ''){
    include_once '../../models/global_vars_model.php';
    $global_var_model = new GlobalVarsModel();
    $target_global_var = $global_var_model->GetglobalVar($id);
    if($target_global_var === false)
        $error = 'Variable global no encontrada';
}

if($error !== ''){
    header("Location: $base_url/views/tables/search_global_var.php?error=$error");
    exit;
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';
include_once '../../fields_config/global_vars.php';

$formBuilder = new FormBuilder(
    '../../controllers/update_global_var.php',    
    'POST',
    'Actualizar variable global',
    'Actualizar',
    '',
    $globalVarFields
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
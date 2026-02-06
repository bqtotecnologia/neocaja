<?php 
$admitted_user_types = ['Cajero', 'Super', 'SENIAT'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/Validator.php';

$error = '';
$id = Validator::ValidateRecievedId();
if(is_string($id)){
    $error = $id;
}

if($error === ''){
    include_once '../../models/global_vars_model.php';
    $global_vars_model = new GlobalVarsModel();
    $target_global_var = $global_vars_model->GetGlobalVar($id);
    
    if($target_global_var === false)
        $error = 'Variable global no encontrada';
}

if($error !== ''){
    header("Location: $base_url/views/tables/search_global_var.php?error=$error");
    exit;
}

include '../../views/common/header.php';

$global_var_history = $global_vars_model->GetGlobalVarHistory($target_global_var['id']);
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Historial de valores de la variable global "<?= $target_global_var['name'] ?>"</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <?php $btn_url = 'search_global_var.php'; include_once '../layouts/backButton.php'; ?>
            <div class="table-responsive">
                <?php include '../common/tables/global_var_history_table.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
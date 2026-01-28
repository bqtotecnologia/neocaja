<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';

include '../../views/common/header.php';

include_once '../../models/global_vars_model.php';
$globals_model = new GlobalVarsModel();
$global_vars = $globals_model->GetGlobalVars();
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Lista de las variables globales</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <div class="table-responsive">
                <?php include '../common/tables/global_var_table.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
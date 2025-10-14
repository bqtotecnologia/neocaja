<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';

include '../../views/common/header.php';
include_once '../../models/unknown_incomes_model.php';
$unknown_model = new UnknownIncomesModel();

$generations = $unknown_model->GetUnknownIncomesGenerations();
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Generaciones de ingresos no identificados</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <?php 
            $btn_url = '../forms/import_unknown_incomes.php'; 
            include_once '../layouts/addButton.php';
            include '../common/tables/unknown_incomes_generations_table.php'; 
            ?>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

include '../../views/common/header.php';
include_once '../../models/sale_point_model.php';
$sale_point_model = new SalePointModel();

$sale_points = $sale_point_model->GetSalePoints();
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Lista de los puntos de venta</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <?php 
            $btn_url = '../forms/sale_point_form.php'; 
            include_once '../layouts/addButton.php';
            include '../common/tables/sale_point_table.php';
            ?>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
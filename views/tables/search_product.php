<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';

include '../../views/common/header.php';
include_once '../../models/product_model.php';
$product_model = new ProductModel();

$products = $product_model->GetAllProducts();
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Lista de los productos</h1>
    </div>

    <?php include_once '../../utils/message_displayer.php'; ?>

    
    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <?php $btn_url = '../forms/product_form.php'; include_once '../../utils/partials/addButton.php' ?>
            <?php include '../common/tables/products_table.php'; ?>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
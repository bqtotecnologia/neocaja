<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/Validator.php';

include '../../views/common/header.php';

$id = Validator::ValidateRecievedId();
if(is_string($id)){
    header("Location: " . $base_url . "/views/tables/search_product.php?error=$id");
    exit;
}

include_once '../../models/product_model.php';
$product_model = new ProductModel();
$target_product = $product_model->GetProduct($id);

if($target_product === false){
    header('Location: ' . $base_url . '/views/tables/search_product.php?error=Producto no encontrado');
    exit;
}

$product_history = $product_model->GetProductHistory($target_product['id']);
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Historial de precios del producto "<?= $target_product['name'] ?>"</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <?php 
            $btn_url = 'search_product.php'; 
            include_once '../layouts/backButton.php';
            include '../common/tables/product_history_table.php'; 
            ?>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
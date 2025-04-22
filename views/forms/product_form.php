<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';

include '../common/header.php';
include_once '../../utils/base_url.php';

$edit = isset($_GET['id']);
if($edit){
    if(!is_numeric($_GET['id'])){
        header('Location: ' . $base_url . '/views/tables/search_product.php?error=Id inválido');
        exit;
    }   

    include_once '../../models/product_model.php';
    $product_model = new ProductModel();
    $target_product = $product_model->GetProduct($_GET['id']);
    if($target_product === false){
        header("Location: $base_url/views/panel?error=Producto no encontrado");
        exit;
    }
}

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <div class="col-12 x_title">
            <a href="../tables/search_product.php" class="btn btn-app">
                <i class="fa fa-arrow-left text-success"></i> Ver listado
            </a>
        </div>
    </div>
    <div class="col-12 text-center">
        <h1 class="h1 text-black">
            <?php if($edit) echo 'Editar '; else echo 'Registrar nuevo '; ?> producto
        </h1>
    </div>

    <?php if(isset($_GET['error'])) { ?>
        <div class="alert alert-danger alert-dismissible h6" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            <?= $_GET['error'] ?>
        </div>
    <?php } ?>
    
    <div class="col-12 justify-content-center px-5">
        <form 
        action="../../controllers/handle_product.php" 
        method="POST" 
        id="criterio-form" 
        data-parsley-validate 
        class="form-horizontal form-label-left d-flex justify-content-center align-items-center flex-column x_panel">
            <?php if($edit === true) { ?>
                <input type="hidden" name="id" value="<?= $target_product['id'] ?>">
            <?php } ?>

            <div class="row col-8 my-2 justify-content-start">
                <div class="col-12 col-lg-3 d-flex align-items-center justify-content-center justify-content-lg-end fw-bold">
                    <label class="m-0" for="name">Nombre</label>
                </div>
                <div class="col-12 col-lg-9">
                    <input class="col-12 col-lg-8 form-control" name="name" id="name" type="text" placeholder="Nombre" value="<?php if($edit) echo $target_product['name']; ?>">
                </div>
            </div>

            <div class="row col-8 my-2 justify-content-start">
                <div class="col-12 col-lg-3 d-flex align-items-center justify-content-center justify-content-lg-end fw-bold">
                    <label class="m-0" for="price">Precio</label>
                </div>
                <div class="col-12 col-lg-4">
                    <input class="col-12 col-lg-6 form-control" name="price" id="price" type="number" placeholder="Precio ($)" value="<?php if($edit) echo $target_product['price']; ?>" step="0.1">
                </div>                    
            </div>

            <div class="ln_solid"></div>
            <div>
                <h3 class="text-danger" id="error-displayer"></h3>
            </div>
            <div class="item form-group col-12 text-center">
                <div class="col-md-6 col-sm-6 offset-md-3">
                    <button type="submit" class="btn btn-success">
                        <?php if($edit) echo 'Editar'; else echo 'Registrar'; ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>
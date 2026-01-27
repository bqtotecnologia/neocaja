<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';

$edit = isset($_GET['id']);
$form = true;
$error = '';

if($edit){
    include_once '../../utils/Validator.php';
    $id = Validator::ValidateRecievedId();

    if(is_string($id))
        $error = $id;
}

if($error === '' && $edit){
    include_once '../../models/product_model.php';
    $product_model = new ProductModel();
    $target_product = $product_model->GetProduct($id);
    if($target_product === false)
        $error = 'Producto no encontrado';
}

if($error !== ''){
    header("Location: $base_url/views/tables/search_product.php?error=$error");
    exit;
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';
include_once '../../fields_config/products.php';


$formBuilder = new FormBuilder(
    '../../controllers/handle_product.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nuevo') . ' producto',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $productFields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_product.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>


<script>
    const nameInput = document.getElementById('name')
    if(nameInput.value !== ''){
        if(nameInput.value.includes('Mensualidad') || nameInput.value === 'FOC')
            nameInput.readOnly = true
    }
</script>

<?php include_once '../common/footer.php'; ?>
<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';

include '../common/header.php';
include_once '../../utils/base_url.php';
include_once '../../utils/FormBuilder.php';


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

$fields = [
    [
        'name' => 'name',
        'display' => 'Nombre',
        'placeholder' => 'Nombre del producto',
        'id' => 'name',
        'type' => 'text',
        'size' => 8,
        'max' => 255,
        'min' => 1,
        'required' => true,
        'value' => $edit ? $target_product['name'] : ''
    ],
    [
        'name' => 'price',
        'display' => 'Precio',
        'placeholder' => 'Precio ($)',
        'id' => 'price',
        'type' => 'decimal',
        'size' => 4,
        'required' => true,
        'value' => $edit ? $target_product['price'] : ''
    ],
    [
        'name' => 'prueba',
        'display' => 'Prueba',
        'placeholder' => '',
        'id' => 'prueba',
        'type' => 'checkbox',
        'size' => 6,
        'required' => true,
        'value' => ['1' => '1', '2' => '2', '3' => '3'],
        'elements' => [
            [
                'name' => '1',
                'display' => 'primera',
                'value' => '1'
            ],
            [
                'name' => '2',
                'display' => 'segunda',
                'value' => '2'
            ],
            [
                'name' => '3',
                'display' => 'tercera',
                'value' => '3'
            ],
        ]
    ],
];

$formBuilder = new FormBuilder(
    '../../controllers/handle_product.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nuevo') . ' producto',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $fields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <div class="col-12 x_title">
            <a href="../tables/search_product.php" class="btn btn-app">
                <i class="fa fa-arrow-left text-success"></i> Ver listado
            </a>
        </div>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>
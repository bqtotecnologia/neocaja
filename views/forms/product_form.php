<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';


$edit = isset($_GET['id']);
if($edit){
    include_once '../../utils/Validator.php';
    $id = Validator::ValidateRecievedId();

    if(is_string($id)){
        header("Location: $base_url/views/tables/search_product.php?error=$id");
        exit;
    }   

    include_once '../../models/product_model.php';
    $product_model = new ProductModel();
    $target_product = $product_model->GetProduct($id);
    if($target_product === false){
        header("Location: $base_url/views/tables/search_product.php?error=Producto no encontrado");
        exit;
    }
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';

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
        'name' => 'active',
        'display' => 'Activo',
        'placeholder' => '',
        'id' => 'active',
        'type' => 'checkbox',
        'size' => 4,
        'required' => false,
        'value' => $edit ? [$target_product['active']] : ['1'],
        'elements' => [
            [
                'display' => 'Activo',
                'value' => '1'
            ]
        ]
    ],
];

if($edit){
    $id_field = [
        'name' => 'id',
        'value' => $target_product['id']
    ];
    array_push($fields, $id_field);
}

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
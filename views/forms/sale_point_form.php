<?php
$admitted_user_types = ['Super', 'Cajero'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

$edit = isset($_GET['id']);
if($edit){
    if(!is_numeric($_GET['id'])){
        header("Location: $base_url/views/tables/search_sale_point.php?error=Id inválido");
        exit;
    }   

    include_once '../../models/sale_point_model.php';
    $sale_point_model = new SalePointModel();
    $target_sale_point = $sale_point_model->GetSalePoint($_GET['id']);
    if($target_sale_point === false){
        header("Location: $base_url/views/tables/search_sale_point.php?error=Punto de venta no encontrado");
        exit;
    }
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';

$fields = [
    [
        'name' => 'code',
        'display' => 'Código',
        'placeholder' => 'Código del punto de venta',
        'id' => 'code',
        'type' => 'text',
        'size' => 5,
        'min' => 1,
        'max' => 8,
        'required' => true,
        'value' => $edit ? $target_sale_point['code'] : ''
    ],    
];

if($edit){
    $id_field = [
        'name' => 'id',
        'value' => $target_sale_point['id']
    ];
    array_push($fields, $id_field);
}

$formBuilder = new FormBuilder(
    '../../controllers/handle_sale_point.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nuevo') . ' punto de venta',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $fields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_sale_point.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>
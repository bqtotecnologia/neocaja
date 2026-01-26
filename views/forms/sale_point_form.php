<?php
$admitted_user_types = ['Super', 'Cajero'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

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
    include_once '../../models/sale_point_model.php';    
    $sale_point_model = new SalePointModel();    

    $target_sale_point = $sale_point_model->GetSalePoint($id);
    if($target_sale_point === false)
        $error = 'Punto de venta no encontrado';
}

if($error !== ''){
    header("Location: $base_url/views/tables/search_sale_point.php?error$error");
    exit;
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';

include_once '../../models/bank_model.php';
$bank_model = new BankModel();

$banks = $bank_model->GetAllbanks();

$display_banks = [];

foreach($banks as $bank){
    array_push($display_banks,
    [
        'display' => $bank['name'],
        'value' => $bank['id']
    ]);
}

include_once '../../fields_config/sale_points.php';

$formBuilder = new FormBuilder(
    '../../controllers/handle_sale_point.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nuevo') . ' punto de venta',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $salePointFields
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
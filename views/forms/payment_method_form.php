<?php
$admitted_user_types = ['Super', 'Cajero'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

$edit = isset($_GET['id']);
if($edit){
    if(!is_numeric($_GET['id'])){
        header("Location: $base_url/views/tables/search_payment_method.php?error=Id inválido");
        exit;
    }   

    include_once '../../models/payment_method_model.php';
    $payment_method_model = new PaymentMethodModel();
    $target_payment_method = $payment_method_model->GetPaymentMethodType($_GET['id']);
    if($target_payment_method === false){
        header("Location: $base_url/views/tables/search_payment_method.php?error=Método de pago no encontrado");
        exit;
    }
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';

$fields = [
    [
        'name' => 'name',
        'display' => 'Nombre',
        'placeholder' => '',
        'id' => 'name',
        'type' => 'text',
        'size' => 8,
        'max' => 255,
        'min' => 5,
        'required' => true,
        'value' => $edit ? $target_payment_method['name'] : ''
    ],    
];

if($edit){
    $id_field = [
        'name' => 'id',
        'value' => $target_payment_method['id']
    ];
    array_push($fields, $id_field);
}

$formBuilder = new FormBuilder(
    '../../controllers/handle_payment_method.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nuevo') . ' método de pago',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $fields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_payment_method.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php if($edit) { ?>
    <?php if(in_array($target_payment_method['name'], ['Efectivo', 'Pago móvil', 'Transferencia', 'Tarjeta de débito'])) { ?>
        <script>
            document.getElementById('name').readOnly = true
        </script>
    <?php } ?>
<?php } ?>

<?php include_once '../common/footer.php'; ?>
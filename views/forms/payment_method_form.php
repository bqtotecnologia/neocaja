<?php
$admitted_user_types = ['Super', 'Cajero'];
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
    include_once '../../models/payment_method_model.php';
    $payment_method_model = new PaymentMethodModel();
    $target_payment_method = $payment_method_model->GetPaymentMethodType($id);
    if($target_payment_method === false){
        $error = 'Método de pago no encontrado';
    }
}

if($error !== ''){
    header("Location: $base_url/views/tables/search_payment_method.php?error=$error");
    exit;
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';
include_once '../../fields_config/payment_methods.php';

$formBuilder = new FormBuilder(
    '../../controllers/handle_payment_method.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nuevo') . ' método de pago',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $paymentMethodFields
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
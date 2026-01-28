<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';

include '../../views/common/header.php';

include_once '../../models/payment_method_model.php';
$payment_method_model = new PaymentMethodModel();
$payment_method_types = $payment_method_model->GetAllPaymentMethodTypes();
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Lista de los métodos de pago</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <?php $btn_url = '../forms/payment_method_form.php'; include_once '../layouts/addButton.php'; ?>
            <div class="table-responsive">
                <?php include '../common/tables/payment_method_table.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
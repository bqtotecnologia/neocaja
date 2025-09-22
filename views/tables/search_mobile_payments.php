<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';

include '../../views/common/header.php';
include_once '../../models/mobile_payments_model.php';
$mobile_payments_model = new MobilePaymentsModel();

$mobile_payments = $mobile_payments_model->GetAllMobilePayments();
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Pago móviles para recibir pagos</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <?php 
            $btn_url = '../forms/mobile_payment_form.php'; 
            include_once '../layouts/addButton.php';
            include '../common/tables/mobile_payment_table.php'; 
            ?>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
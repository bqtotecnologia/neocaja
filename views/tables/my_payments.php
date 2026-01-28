<?php 
$admitted_user_types = ['Estudiante', 'Super'];
include_once '../../utils/validate_user_type.php';

include_once '../../utils/prettyCiphers.php';

include '../../views/common/header.php';

include_once '../../models/account_payments_model.php';
$payment_model = new AccountPaymentsModel();
$payments = $payment_model->GetPaymentsOfAccount($_SESSION['neocaja_cedula']);

?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Histórico de pagos realizados</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">            
            <?php $btn_url = $base_url . '/views/panel.php'; include_once '../layouts/backButton.php'; ?>
            <div class="table-responsive">
                <?php include '../common/tables/account_payments_table.php';  ?>
            </div>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
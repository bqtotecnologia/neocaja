<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/Validator.php';
include_once '../../utils/prettyCiphers.php';

include '../../views/common/header.php';

include_once '../../models/account_payments_model.php';
$payment_model = new AccountPaymentsModel();

if(isset($_GET['state']))
    $payments = $payment_model->GetPaymentsOfState($_GET['state']);
else{
    header("Location: $base_url/views/panel.php");
    exit;
}
    

?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Pagos remotos <?= $_GET['state'] ?></h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">            
            <?php 
            $btn_url = 'search_coin.php'; 
            include_once '../layouts/backButton.php';
            ?>
            <div class="table-responsive">
                <?php include '../common/tables/account_payments_table.php';  ?>
            </div>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
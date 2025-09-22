<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';

include '../../views/common/header.php';
include_once '../../models/transfers_model.php';
$transfer_model = new TransfersModel();

$transfers = $transfer_model->GetAllTransfers();
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Cuentas para recibir transferencias</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <?php 
            $btn_url = '../forms/transfer_form.php'; 
            include_once '../layouts/addButton.php';
            include '../common/tables/transfer_table.php'; 
            ?>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
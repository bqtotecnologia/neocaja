<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';

include '../../views/common/header.php';
include_once '../../models/bank_model.php';
$bank_model = new BankModel();

$banks = $bank_model->GetAllBanks();
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Lista de los bancos</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <?php $btn_url = '../forms/bank_form.php'; include_once '../layouts/addButton.php'; ?>
            <div class="table-responsive">
                <?php include '../common/tables/bank_table.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
<?php 
$admitted_user_types = ['Cajero', 'Super', 'SENIAT'];
include_once '../../utils/validate_user_type.php';

include '../../views/common/header.php';

include_once '../../models/invoice_model.php';
$invoice_model = new InvoiceModel();
$invoices = $invoice_model->GetInvoicesOfDate(date('Y-m-d'));
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Facturas del día de hoy (<?= date('d/m/Y') ?>)</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <div class="table-responsive">
                <?php if($_SESSION['neocaja_rol'] !== 'SENIAT') { ?>
                    <?php $btn_url = '../forms/invoice_form.php'; include_once '../layouts/addButton.php'; ?>
                <?php } ?>
                <div class="table-responsive">
                    <?php include '../common/tables/invoice_table.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
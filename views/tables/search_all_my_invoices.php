<?php 
$admitted_user_types = ['Estudiante'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

include '../../views/common/header.php';

include_once '../../models/invoice_model.php';
include_once '../../models/account_model.php';
include_once '../../models/siacad_model.php';

$invoice_model = new InvoiceModel();
$account_model = new AccountModel();
$siacad = new SiacadModel();

$current_period = $siacad->GetCurrentPeriodo();

$target_account = $account_model->GetAccountByCedula($_SESSION['neocaja_cedula']);

if($target_account === false){
    header("$base_url/views/panel.php?error=Cuenta no encontrada");
    exit;
}

$invoices = $invoice_model->GetInvoicesOfAccount($target_account['id']);
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Todas tus facturas registradas</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <div class="table-responsive">
                <?php 
                $btn_url = '../views/panel.php'; 
                include '../common/tables/invoice_table.php';
                ?>
            </div>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
<?php 
$admitted_user_types = ['Estudiante'];
include_once '../../utils/validate_user_type.php';

$error = '';
include_once '../../utils/Validator.php';
if(Validator::HasSuspiciousCharacters($_SESSION['neocaja_cedula']))
    $error = 'La variable de sesión de la cédula contiene caracteres sospechosos';

if($error === ''){
    include_once '../../models/account_model.php';
    $account_model = new AccountModel();
    $target_account = $account_model->GetAccountByCedula($_SESSION['neocaja_cedula']);
    if($target_account === false)
        $error = 'Cuenta no encontrada';
}

if($error !== ''){
    header("$base_url/views/panel.php?error=$error");
    exit;
}

include_once '../../models/invoice_model.php';
include_once '../../models/siacad_model.php';

$invoice_model = new InvoiceModel();
$siacad = new SiacadModel();

$current_period = $siacad->GetCurrentPeriodo();

include '../../views/common/header.php';

$invoices = $invoice_model->GetInvoicesOfAccount($target_account['id']);
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Todas tus facturas registradas</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <div class="table-responsive">
                <?php include '../common/tables/invoice_table.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
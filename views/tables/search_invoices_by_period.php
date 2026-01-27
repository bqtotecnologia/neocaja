<?php 
$admitted_user_types = ['Estudiante', 'Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

include_once '../../models/account_model.php';
$account_model = new AccountModel();

$student = $_SESSION['neocaja_rol'] === 'Estudiante';

if($student){
    $target_account = $account_model->GetAccountByCedula($_SESSION['neocaja_cedula']);
    
    if($target_account === false){
        header("Location: $base_url/views/panel.php?error=Cuenta no encontrada");
        exit;
    }
}

include_once '../../models/siacad_model.php';
$siacad = new SiacadModel();

$target_period = false;
if(!empty($_POST)) {
    $error = '';
    include_once '../../utils/Validator.php';
    $id = Validator::ValidateRecievedId('id', 'POST');
    if(is_string($id))
        $error = 'Periodo inválido';

    if($error === ''){
        $target_period = $siacad->GetPeriodoById($id);
        if($target_period === false)
            $error = 'Periodo no encontrado';
    }

    if($error !== ''){
        header("Location: $base_url/views/tables/search_invoices_by_period.php?error=$error");
        exit;
    }
}

include_once '../../models/invoice_model.php';
$invoice_model = new InvoiceModel();

include '../../views/common/header.php';

$periods = $invoice_model->GetPeriodsOfInvoices();
$periods = $siacad->GetPeriodsByIdList($periods);
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Buscar facturas por periodo</h1>
    </div>    
    <div class="col-12 row justify-content-center px-4">
        <form class="col-12 row justify-content-center x_panel" method="POST" action="<?= $base_url ?>/views/tables/search_invoices_by_period.php">
            <div class="row col-12 m-0 p-0 my-2">
                <div class="col-3 col-md-5 text-right align-middle d-flex align-items-center justify-content-end p-0">
                    <label class="fw-bold h6" for="id">
                        Periodo:
                    </label>
                </div>
                <div class="col-8 col-md-3 px-2">
                    <select class="select2" name="id">
                        <option value="">&nbsp;</option>
                        <?php foreach($periods as $period) { ?>
                            <option value="<?= $period['idperiodo'] ?>" <?php if($target_period !== false) { if($target_period['idperiodo'] === $period['idperiodo']) echo 'selected'; } ?>>
                                <?= $period['nombreperiodo'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row col-12 m-0 p-0 my-2 justify-content-center py-2">
                <button class="btn btn-success">
                    Buscar
                </button>
            </div>
        </form>
    </div>

    <?php if($target_period !== false) { ?>
        <?php
        if($student)
            $invoices = $invoice_model->GetInvoicesOfAccountOfPeriod($target_account['id'], $target_period['idperiodo']); 
        else
            $invoices = $invoice_model->GetInvoicesOfPeriod($target_period['idperiodo']);
        ?>

        <div class="col-12 row justify-content-center px-4">
            <div class="col-12 row justify-content-center x_panel">
                <div class="table-responsive">
                    <?php include '../common/tables/invoice_table.php'; ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php include '../common/footer.php'; ?>
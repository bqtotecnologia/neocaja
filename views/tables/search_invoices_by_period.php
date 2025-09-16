<?php 
$admitted_user_types = ['Estudiante', 'Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

include_once '../../models/account_model.php';
$account_model = new AccountModel();


$target_account = $account_model->GetAccountByCedula($_SESSION['neocaja_cedula']);

if($target_account === false){
    header("$base_url/views/panel.php?error=Cuenta no encontrada");
    exit;
}

if(!empty($_POST)) {
    // verificar que el periodo seleccionado existe y otras validaciones de usuario
}

include '../../views/common/header.php';

include_once '../../models/invoice_model.php';
include_once '../../models/siacad_model.php';

$invoice_model = new InvoiceModel();
$siacad = new SiacadModel();

$periods = $invoice_model->GetPeriodsOfInvoices();
$periods = $siacad->GetPeriodsByIdList($periods);

//$invoices = $invoice_model->GetInvoicesOfAccountOfPeriod($target_account['id'], $current_period['idperiodo']);
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Buscar facturas por periodo</h1>
    </div>    
    <div class="col-12 row justify-content-center px-4">
        <form class="col-12 row justify-content-center x_panel" method="POST">
            <div class="row col-12 m-0 p-0 my-2">
                <div class="col-4 text-right align-middle d-flex align-items-center justify-content-end p-0">
                    <label class="fw-bold h6" for="fecha_nacimiento">
                        Periodo:
                    </label>
                </div>
                <div class="col-8 col-lg-2 px-2">
                    <select class="select2" name="" id="">
                        <?php foreach($periods as $period) { ?>
                            <option value="<?= $period['idperiodo'] ?>"><?= $period['nombreperiodo'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <?php if(!empty($_POST)) { ?>
        <?php 
            
        ?>
        <div class="col-12 row justify-content-center px-4">
            <div class="col-12 row justify-content-center x_panel">
                <?php 
                //include '../common/tables/invoice_table.php';
                ?>
            </div>
        </div>
    <?php } ?>
</div>

<?php include '../common/footer.php'; ?>
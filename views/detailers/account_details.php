<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/Validator.php';
include_once '../../utils/prettyCiphers.php';

$error = '';

$id = Validator::ValidateRecievedId();
if(is_string($id)){
    $error = $id;    
}

if($error === ''){
    include_once '../../models/account_model.php';
    $account_model = new AccountModel();
    $target_account = $account_model->GetAccount($id);
    if($target_account === false)
        $error = 'Cliente no encontrado';
}

if($error !== ''){
    header("Location: $base_url/views/tables/search_account.php?error=$error");
    exit;
}

include_once '../../models/invoice_model.php';
include_once '../../models/siacad_model.php';
include_once '../../models/account_payments_model.php';
include_once '../../models/product_model.php';
include_once '../../models/coin_model.php';

$invoice_model = new InvoiceModel();
$siacad = new SiacadModel();
$payment_model = new AccountPaymentsModel();
$product_model = new ProductModel();
$coin_model = new CoinModel();

$currentPeriod = $siacad->GetCurrentPeriodo();
$focProduct = $product_model->GetProductByName('FOC');
$monthStates = $invoice_model->GetAccountState($target_account['cedula'], $currentPeriod['idperiodo']);
$debtState = $invoice_model->GetDebtOfAccountOfPeriod($target_account['cedula'], $currentPeriod['idperiodo']);

$usd = $coin_model->GetCoinByName('Dólar');
$coin_date = date('Y-m-d', strtotime($usd['price_created_at']));
$today = date('Y-m-d');
$usdUpdated = strtotime($today) === strtotime($coin_date);
$total_debt = $debtState['months']['total'] + $debtState['retard']['total'];

if($debtState['foc'] === false)
    $total_debt += $focProduct['price'];

$invoices = $invoice_model->GetInvoicesOfAccount($target_account['id']);
$target_student = $siacad->GetEstudianteByCedula($target_account['cedula']);
$payments = $payment_model->GetPaymentsOfAccount($target_student['cedula']);

include '../../views/common/header.php';

?>

<div class="row justify-content-center">
    
    <div class="col-12 row justify-content-center">
        <?php $btn_url = $base_url . '/views/tables/search_account.php'; include_once '../layouts/backButton.php'; ?>
    </div>

    <div class="col-12 text-center mt-4">
        <h1 class="h1 text-black">Datos de cliente </h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <section class="col-12 row justify-content-center h6 bg-white py-2" style="border: 1px solid #d6d6d6ff !important">
            <div class="row col-6 justify-content-center align-self-start">
                <div class="row col-10 justify-content-start align-items-start">
                    <label class="fw-bold mx-2">
                        Nombre completo:
                    </label>
                    <span class="">
                        <?= $target_account['names'] . ' ' . $target_account['surnames'] ?>
                    </span>
                </div>

                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Cédula:
                    </label>
                    <span class="">
                        <?= $target_account['cedula'] ?>
                    </span>
                </div>

                <?php if($target_account['company'] !== null) { ?>
                    <div class="row col-10 justify-content-start align-items-middle">
                        <label class="fw-bold mx-2">
                            Empresa:
                        </label>
                        <span class="">
                            <?= $target_account['company'] ?>
                        </span>
                    </div> 
                    
                    <div class="row col-10 justify-content-start align-items-middle">
                        <label class="fw-bold mx-2">
                            RIF:
                        </label>
                    <span class="">
                            <?= $target_account['rif_letter'] . $target_account['rif_number'] ?>
                        </span>
                    </div> 
                <?php } ?>

                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Dirección:
                    </label>
                    <span class="">
                        <?= $target_account['address'] ?>
                    </span>
                </div>

                
            </div>

            <div class="row col-6 justify-content-center align-self-start">
                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Beca:
                    </label>
                    <span class="">
                        <?= $target_account['scholarship'] . ' ' . intval($target_account['scholarship_coverage']) . '%' ?>
                    </span>
                </div>

                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Teléfono:
                    </label>
                    <span class="">
                        <?= $target_account['phone'] ?>
                    </span>
                </div>
                
                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Estudiante del IUJO:
                    </label>
                    <span class="">
                        <?php if($target_student === false) { ?>
                            <i class="fa fa-cancel text-danger"></i>
                        <?php } else { ?>
                            <i class="fa fa-check text-success"></i>
                        <?php } ?>
                    </span>
                </div>

                <?php if($target_student !== false) { ?>
                    <div class="row col-10 justify-content-start align-items-middle">
                        <label class="fw-bold mx-2">
                            Carrera:
                        </label>
                        <span class="">
                            <?= $target_student['carrera'] ?>
                        </span>
                    </div>
                <?php } ?>

                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Fecha de registro:
                    </label>
                    <span class="">
                        <?= date('d/m/Y', strtotime($target_account['created_at'])) ?>
                    </span>
                </div>
            </div>
        </section>        


        <section class="col-12 row justify-content-center h6 bg-white py-2" style="border: 1px solid #d6d6d6ff !important">
            <div class="row col-12 col-md-6 m-0 p-0 justify-content-center">
                <div class="row m-0 p-0 col-12 justify-content-center my-2 p-2" id="debt-container">
                    <?php include_once "../common/tables/account_debt_table.php"; ?>                    
                </div>
            </div>

            <div class="row m-0 p-0 col-12 col-md-6 my-2 justify-content-start">
                <div class="row m-0 p-0 col-12 p-2 justify-content-center" id="invoices">
                    <table class="table table-bordered border border-black">
                        <thead class="text-center bg-theme text-white">
                            <tr class="h5 m-0">
                                <th class="border border-black">Mes</th>
                                <th class="border border-black">Pagado</th>
                                <th class="border border-black">Moroso</th>
                                <th class="border border-black">Abonado</th>
                            </tr>
                        </thead>
                        <tbody id="invoice-table">
                            <?php foreach($monthStates as $month => $state) { ?>
                                <tr class="text-center fs-5 text-black">
                                    <td class="p-1 border border-black bg-white align-middle text-black"><?= $month ?></td>
                                    <td class="p-1 border border-black bg-white align-middle">
                                        <i class="fa text-<?= $state['paid'] ? 'success fa-check' : 'danger fa-close' ?>"></i>
                                    </td>
                                    <td class="p-1 border border-black bg-white align-middle">
                                        <i class="fa text-<?= $state['debt'] ? 'success fa-check' : 'danger fa-close' ?>"></i>
                                    </td>
                                    <td class="p-1 border border-black bg-white align-middle">
                                        <i class="fa text-<?= $state['partial'] ? 'success fa-check' : 'danger fa-close' ?>"></i>
                                    </td>
                                </tr>                                    
                            <?php } ?>
                    </table>
                </div>
            </div>
        </section>


        <section class="col-12 row justify-content-center h6 bg-white py-2" style="border: 1px solid #d6d6d6ff !important">
            <div class="row col-12 text-center">
                <h2 class="w-100 text-center h2">Facturas registradas</h2>
            </div>

            <div class="row col-12 justify-content-center">
                <div class="table-responsive">
                    <?php include_once '../common/tables/invoice_table.php'; ?>
                </div>
            </div>
        </section>

        <section class="col-12 row justify-content-center h6 bg-white py-2" style="border: 1px solid #d6d6d6ff !important">
            <div class="row col-12 text-center">
                <h2 class="w-100 text-center h2">Pagos remotos del estudiante</h2>
            </div>

            <div class="row col-12 justify-content-center">
                <div class="table-responsive">
                    <?php include_once '../common/tables/account_payments_table.php'; ?>
                </div>
            </div>
        </section>
    </div>

</div>

<?php include '../common/footer.php'; ?>
<?php 
$admitted_user_types = ['Cajero', 'Super', 'Estudiante', 'SENIAT', 'Administrador'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/Validator.php';
include_once '../../utils/months_data.php';
include_once '../../utils/prettyCiphers.php';

$id = Validator::ValidateRecievedId();
$error = '';

if(is_string($id)){
    $error = $id;    
}

include_once '../../models/invoice_model.php';
include_once '../../models/account_model.php';
include_once '../../models/siacad_model.php';
include_once '../../models/coin_model.php';

$invoice_model = new InvoiceModel();
$account_model = new AccountModel();
$siacad = new SiacadModel();
$coin_model = new CoinModel();

$target_invoice = $invoice_model->GetInvoice($id);
if($target_invoice === false){
    $error = 'Factura no encontrada';
}

if($_SESSION['neocaja_rol'] === 'Estudiante'){
    if($target_invoice['cedula'] !== $_SESSION['neocaja_cedula']){
        $error = 'Factura inválida';
    }
}

if($error !== ''){
    if($_SESSION['neocaja_rol'] !== 'Estudiante')
        header('Location: ' . $base_url . '/views/tables/search_invoices_of_today.php?error='. $error);
    else
        header('Location: ' . $base_url . '/views/panel.php?error='. $error);
    exit;
}

$target_period = $siacad->GetPeriodoById($target_invoice['period']);
$target_credit_note = $invoice_model->GetCreditNoteOfInvoice($target_invoice['id']);
$target_account = $account_model->GetAccount($target_invoice['account_id']);
$payment_methods = $invoice_model->GetPaymentMethodsOfInvoice($id);
$concepts = $invoice_model->GetConceptsOfInvoice($id);
$coinValues = $coin_model->GetCoinValuesOfDate($target_invoice['rate_date']);
$periods = $siacad->GetperiodsAsDict(false);
$igtf = null;

include '../../views/common/header.php';

?>

<div class="row justify-content-center">
    
    <div class="col-12 row justify-content-center">
        <?php 
        if($_SESSION['neocaja_rol'] === 'Estudiante')
            $btn_url = $base_url . '/views/tables/search_my_invoices.php'; 
        else
            $btn_url = $base_url . '/views/tables/search_invoices_of_today.php'; 
        include_once '../layouts/backButton.php'; 
        ?>
    </div>


    <?php if(!in_array($_SESSION['neocaja_rol'], ['Estudiante', 'SENIAT']) ) { ?>
        <div class="row col-12 mb-5 justify-content-around">
            <a class="mx-3" target="_blank" href="<?= $base_url . '/views/exports/export_invoice_as_pdf.php?id=' . $target_invoice['id'] ?>">
                <button class="btn btn-success">
                    Imprimir factura
                </button>
            </a>            
        </div>

    <?php } ?>

    <div class="col-12 text-center mt-4">
        <h1 class="h1 text-black">Factura Nº <?= $target_invoice['invoice_number'] ?></h1>
    </div>

    

    <div class="col-12 row justify-content-center px-4">
        <section class="col-12 row justify-content-center h6 bg-white py-3" style="border: 1px solid #d6d6d6ff !important">
            <div class="row col-6 justify-content-center align-self-start">
                <div class="row col-10 justify-content-start align-items-start">
                    <label class="fw-bold mx-2">
                        Cliente:
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

                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Beca:
                    </label>
                    <?php if($target_account['scholarship'] !== NULL && $target_account['scholarship_coverage'] !== NULL) { ?>
                        <span class="text-success fw-bold">
                            <?= $target_account['scholarship'] . ' ' . $target_account['scholarship_coverage'] ?>%
                        </span>
                    <?php } else { ?>
                        <span class="text-danger fw-bold">
                            NO TIENE
                        </span>
                    <?php } ?>
                </div>

                <?php if($target_account['company'] !== null) { ?>
                    <div class="row col-10 justify-content-start align-items-middle">
                        <label class="fw-bold mx-2">
                            Empresa:
                        </label>
                        <span class="text-info fw-bold">
                            <?= $target_account['company'] ?>
                        </span>
                    </div> 
                    
                    <div class="row col-10 justify-content-start align-items-middle">
                        <label class="fw-bold mx-2">
                            RIF:
                        </label>
                        <span class="text-info fw-bold">
                            <?= $target_account['rif_letter'] . $target_account['rif_number'] ?>
                        </span>
                    </div> 
                <?php } ?>

                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Observación:
                    </label>
                    <span class="">
                        <?= $target_invoice['observation'] ?>
                    </span>
                </div>

                
            </div>

            <div class="row col-6 justify-content-center align-self-start">

                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Número de factura:
                    </label>
                    <span class="">
                        <?= $target_invoice['invoice_number'] ?>
                    </span>
                </div>

                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Número de control:
                    </label>
                    <span class="">
                        <?= $target_invoice['control_number'] ?>
                    </span>
                </div>
                
                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Fecha de emisión:
                    </label>
                    <span class="">
                        <?= date('d/m/Y', strtotime($target_invoice['created_at'])) ?>
                    </span>
                </div>

                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Periodo:
                    </label>
                    <span class="">
                        <?= $target_period['nombreperiodo'] ?>
                    </span>
                </div>

                <div class="row col-10 justify-content-start align-items-middle mt-3">
                    <label class="fw-bold mx-2 mb-0 align-middle">
                        Estado:
                    </label>
                    <span class="fw-bold align-middle text-<?= $target_invoice['invoice_state'] === 'Activa' ? 'success' : 'danger' ?>">
                        <?= $target_invoice['invoice_state'] ?>
                        <?php if($target_invoice['invoice_state'] === 'Reversada') { ?>
                            <a href="<?= $base_url ?>/views/exports/export_invoice_as_pdf.php?id=<?= $target_invoice['id'] ?>&credit_note=1" target="_blank" class="mx-2 text-decoration-underline" title="<?= $target_credit_note['credit_note_reason'] ?>">
                                Ver nota de crédito
                            </a>                            
                        <?php } ?>
                    </span>
                </div>

                <?php if($target_invoice['invoice_state'] === 'Anulada') { ?>
                    <div class="row col-10 justify-content-start align-items-middle">
                        <label class="fw-bold mx-2 mb-0 align-middle">
                            Motivo de anulación:
                        </label>
                        <span class="align-middle">
                            <?= $target_invoice['anull_reason'] ?>
                        </span>
                    </div>
                <?php } ?>

                <?php if($target_invoice['invoice_state'] === 'Reversada') { ?>
                    <div class="row col-10 justify-content-start align-items-middle">
                        <label class="fw-bold mx-2 mb-0 align-middle">
                            Motivo de reverso:
                        </label>
                        <span class="align-middle">
                            <?= $target_credit_note['credit_note_reason'] ?>
                        </span>
                    </div>
                <?php } ?>
            </div>
        </section>

        <section class="col-12 row justify-content-center h6 bg-white py-2" style="border: 1px solid #d6d6d6ff !important">
            <h2 class="col-12 h2">
                Productos
            </h2>

            <div class="row col-12">                
                <div class="table-responsive">
                    <table class="table table-striped col-12">
                        <thead class="bg-theme text-white fw-bold h6">
                            <tr class="text-center">
                                <th>Producto</th>
                                <th>Mes</th>
                                <th>Periodo</th>
                                <th>Monto ($)</th>
                                <th>Tasa</th>
                                <th>Subtotal</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $products_total = 0; ?>
                            <?php foreach($concepts as $concept) { ?>
                                <?php 
                                    $subtotal = round($concept['price'] * $coinValues['Dólar'], 2); 
                                    $total = $subtotal;
                                    $products_total += $total; 
                                ?>
                                <tr class="text-center">
                                    <td><?= $concept['product'] ?></td>
                                    <td>
                                        <?php if($concept['month'] === null) { ?>
                                            <i class="fa fa-close text-danger"></i>
                                        <?php } else { ?>
                                            <?= $month_translate[$concept['month']] ?>
                                        <?php } ?>
                                    </td>
                                    <td class="text-right"><?= $periods[$concept['period']]['nombreperiodo'] ?></td>
                                    <td class="text-right"><?= $concept['price'] ?></td>
                                    <td class="text-right"><?= $coinValues['Dólar'] ?></td>
                                    <td class="text-right"><?= GetPrettyCiphers($subtotal) ?></td>
                                    <td class="text-right">Bs. <?= GetPrettyCiphers(round($total, 2)) ?></td>
                                </tr>
                            <?php } ?>
                            <tr class="fw-bold text-right h5">
                                <td colspan="6">Total:</td>
                                <td>Bs. <?= GetPrettyCiphers(round($products_total, 2)) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="col-12 row justify-content-center h6 bg-white py-2" style="border: 1px solid #d6d6d6ff !important">
            <h2 class="col-12 h2">
                Métodos de pago
            </h2>

            <div class="row col-12">
                <div class="table-responsive">
                    <table class="table table-striped col-12">
                        <thead class="bg-theme text-white fw-bold h6">
                            <tr class="text-center">
                                <th>Método</th>
                                <th>Moneda</th>
                                <th>Banco receptor</th>
                                <th>Punto de venta</th>
                                <th>Número de Documento</th>
                                <th>Monto</th>
                                <th>Tasa</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $payments_total = 0; ?>
                            <?php foreach($payment_methods as $method) { ?>
                                <?php 
                                    if(intval($method['igtf']) === 1){
                                        $igtf = $method;
                                        continue;
                                    }
                                    $total = round($method['price'] * $coinValues[$method['coin']], 2);
                                    $payments_total += $total;                                     
                                ?>

                                <tr class="text-center">
                                    <td><?= $method['payment_method'] ?></td>
                                    <td><?= $method['coin'] ?></td>
                                    <td>
                                        <?php if($method['bank'] === null) { ?>
                                            <i class="fa fa-close text-danger"></i>
                                        <?php } else { ?>
                                            <?= $method['bank'] ?>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if($method['sale_point'] === null) { ?>
                                            <i class="fa fa-close text-danger"></i>
                                        <?php } else { ?>
                                            <?= $method['sale_point'] ?>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if($method['document_number'] === null) { ?>
                                            <i class="fa fa-close text-danger"></i>
                                        <?php } else { ?>
                                            <?= $method['document_number'] ?>
                                        <?php } ?>
                                    </td>
                                    <td class="text-right"><?= $method['price'] ?></td>
                                    <td class="text-right"><?= $coinValues[$method['coin']] ?></td>
                                    <td class="text-right">Bs. <?= GetPrettyCiphers($total) ?></td>
                                </tr>
                            <?php } ?>
                            <tr class="fw-bold text-right h5">
                                <td colspan="7">Total:</td>
                                <td>Bs. <?= GetPrettyCiphers($payments_total) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <?php if($igtf !== null) { ?>

            <section class="col-12 row justify-content-center h6 bg-white py-2" style="border: 1px solid #d6d6d6ff !important">
                <h2 class="col-12 h2">
                    IGTF
                </h2>
    
                <div class="row col-12">
                    <div class="table-responsive">
                        <table class="table table-striped col-12">
                            <thead class="bg-theme text-white fw-bold h6">
                                <tr class="text-center">
                                    <th>Método</th>
                                    <th>Moneda</th>
                                    <th>Banco receptor</th>
                                    <th>Punto de venta</th>
                                    <th>Número de Documento</th>
                                    <th>Monto</th>
                                    <th>Tasa</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center">
                                    <td><?= $igtf['payment_method'] ?></td>
                                    <td><?= $igtf['coin'] ?></td>
                                    <td>
                                        <?php if($igtf['bank'] === null) { ?>
                                            <i class="fa fa-close text-danger"></i>
                                        <?php } else { ?>
                                            <?= $igtf['bank'] ?>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if($igtf['sale_point'] === null) { ?>
                                            <i class="fa fa-close text-danger"></i>
                                        <?php } else { ?>
                                            <?= $igtf['sale_point'] ?>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if($igtf['document_number'] === null) { ?>
                                            <i class="fa fa-close text-danger"></i>
                                        <?php } else { ?>
                                            <?= $igtf['document_number'] ?>
                                        <?php } ?>
                                    </td>
                                    <td class="text-right"><?= $igtf['price'] ?></td>
                                    <td class="text-right"><?= $coinValues[$igtf['coin']] ?></td>
                                    <td class="text-right">Bs. <?= floatval($igtf['price']) * $coinValues[$igtf['coin']] ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        <?php } ?>
    </div>

    <?php if(!in_array($_SESSION['neocaja_rol'], ['Estudiante', 'SENIAT']) ) { ?>
        <?php if($target_invoice['invoice_state'] === 'Activa') { ?>
            <?php if(($target_invoice['rate_date']) === date('Y-m-d')) { ?>
                <div class="row col-12 m-0 p-0 justify-content-center my-4">
                    <form method="POST" action="../../controllers/anull_invoice.php" class="col-12 col-lg-6 text-center border p-2 shadowed" name="delete-form" title="ANULAR FACTURA <?= $target_invoice['invoice_number'] ?>">
                        <input type="hidden" name="id" value="<?= $target_invoice['id'] ?>">
                        <textarea class="form-control mb-3" name="reason" id="reason" placeholder="Motivo de la anulación" required></textarea>
                        <button class="btn btn-danger">
                            Anular factura
                        </button>
                    </form>
                </div>
            <?php } else { ?>
                 <div class="row col-12 m-0 p-0 justify-content-center my-4">
                    <form method="POST" action="../../controllers/revert_invoice.php" class="col-12 col-lg-6 text-center border p-2 shadowed" name="delete-form" title="REVERSAR FACTURA <?= $target_invoice['invoice_number'] ?>">
                        <input type="hidden" name="id" value="<?= $target_invoice['id'] ?>">
                        <textarea class="form-control mb-3" name="reason" id="reason" placeholder="Motivo o descripción de la reversión" required></textarea>
                        <button class="btn btn-danger">
                            Reversar factura con nota de crédito
                        </button>
                    </form>
                </div>
            <?php } ?>
        <?php } ?>
    <?php } ?>
</div>

<?php include '../common/footer.php'; ?>
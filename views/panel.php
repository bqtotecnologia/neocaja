<?php 
$admitted_user_types = ['Cajero', 'Supervisor', 'Estudiante', 'Super'];
include_once '../utils/validate_user_type.php';

include_once 'common/header.php';

?>

<?php if($_SESSION['neocaja_rol'] === 'Estudiante'){ ?>
    <?php 
        include_once '../models/account_model.php';
        include_once '../models/siacad_model.php';
        include_once '../models/global_vars_model.php';
        include_once '../models/product_model.php';
        include_once '../models/coin_model.php';
        include_once '../models/invoice_model.php';
        include_once '../utils/prettyCiphers.php';

        $global_vars_model = new GlobalVarsModel();
        $global_vars = $global_vars_model->GetGlobalVars(true);

        $siacad = new SiacadModel();
        $currentPeriod = $siacad->GetCurrentPeriodo();

        $product_model = new ProductModel();
        $focProduct = $product_model->GetProductByName('FOC');

        $invoice_model = new InvoiceModel();
        $monthStates = $invoice_model->GetAccountState($_SESSION['neocaja_cedula'], $currentPeriod['idperiodo']);
        $debtState = $invoice_model->GetDebtOfAccountOfPeriod($_SESSION['neocaja_cedula'], $currentPeriod['idperiodo']);

        $coin_model = new CoinModel();
        $usd = $coin_model->GetCoinByName('Dólar');
        $coin_date = date('Y-m-d', strtotime($usd['price_created_at']));
        $today = date('Y-m-d');
        $usdUpdated = strtotime($today) === strtotime($coin_date);
        $total_debt = $debtState['months']['total'] + $debtState['retard']['total'];

        $account_model = new AccountModel();
        $target_account = $account_model->GetAccountByCedula($_SESSION['neocaja_cedula']);
        $scholarshipped = !($target_account['scholarship'] === NULL && $target_account['scholarship_coverage'] === NULL);

        if($debtState['foc'] === false)
            $total_debt += $focProduct['price'];
    ?>
    <div class="x_panel row col-12 m-0 p-0 justify-content-center align-items-center pt-2">              
        <div class="row col-12 p-0 m-0 my-2 justify-content-center">
            <h1 class="h1 text-center w-100">Su estado de cuenta en el periodo <?= $currentPeriod['nombreperiodo'] ?></h1>
        </div>
        <div class="row col-12 p-0 m-0 my-2 justify-content-center align-items-center">
            <div class="col-12 col-md-6">
                <?php if($usdUpdated) { ?>
                    <h2 class="h2 text-center w-100">Tasas del día de hoy <?php date('d/m/Y') ?></h2>
                    <div class="row p-0 m-0 col-12 justify-content-center">
                        <table class="table table-bordered col-6 h5 text-center">
                            <tr>
                                <td class="bg-theme text-white">Dólar</td>
                                <td><?= $usd['price'] ?></td>
                            </tr>
                        </table>
                    </div>
                <?php } else { ?>
                    <h2 class="h2 text-center w-100 text-danger">La tasa del dólar aún no ha sido actualizada</h2>
                <?php } ?>
            </div>

            <div class="col-12 col-md-6">
                <h2 class="h2 text-center w-100">Datos sobre la diferencia de la mensualidad</h2>
                <div class="row p-0 m-0 col-12 justify-content-center">
                    <table class="table table-bordered col-12 h5 text-center">
                        <tr>
                            <td class="bg-theme text-white">Día límite antes de aplicar la diferencia de mensualidad</td>
                            <td><?= intval($global_vars['Dia tope mora']) ?></td>
                        </tr>
                        <tr>
                            <td class="bg-theme text-white">Porcentaje de la diferencia de mensualidad</td>
                            <td><?= intval($global_vars['Porcentaje mora']) ?>%</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="row col-12 col-md-6 m-0 p-0 justify-content-center">
            <div class="row m-0 p-0 col-12 justify-content-center my-2 p-2" id="debt-container">
                <table class="col-12 col-md-10 table table-bordered border border-black text-center h6">
                    <thead>
                        <tr class="bg-theme text-white fw-bold">
                            <th>Producto</th>
                            <th>Bolívares</th>
                            <th>Dólares</th>
                        </tr>
                    </thead>
                    <tbody id="debt-table">
                        <tr>
                            <td class="p-1 border border-black bg-theme text-white fw-bold align-middle">Mensualidad</td>
                            <td class="p-1 border border-black text-<?= $debtState['months']['total'] > 0 ? 'danger' : 'success' ?>">
                                <div class="d-flex justify-content-center flex-wrap">
                                    <?php if($debtState['months']['total'] > 0) { ?>
                                        <?php foreach($debtState['months']['detail'] as $month => $debt) { ?>
                                            <span class="col-6 p-0"><?= $month ?></span>
                                            <span class="col-6 p-0">Bs. <?= $debt * $usd['price'] ?></span>
                                        <?php } ?>
                                    <?php } else { ?>
                                        SIN DEUDA
                                    <?php } ?>
                                </div>
                            </td>
                            <td class="p-1 border border-black text-<?= $debtState['months']['total'] > 0 ? 'danger' : 'success' ?>">
                                <div class="d-flex justify-content-center flex-wrap">
                                    <?php if($debtState['months']['total'] > 0) { ?>
                                        <?php foreach($debtState['months']['detail'] as $month => $debt) { ?>
                                            <span class="col-6 p-0"><?= $month ?></span>
                                            <span class="col-6 p-0"><?= $debt ?>$</span>
                                        <?php } ?>
                                    <?php } else { ?>
                                        SIN DEUDA
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="p-1 border border-black bg-theme text-white fw-bold align-middle">Diferencia mensualidad</td>
                            <td class="p-1 border border-black text-<?= $debtState['retard']['total'] > 0 ? 'danger' : 'success' ?>">
                                <div class="d-flex justify-content-center flex-wrap">
                                    <?php if($debtState['retard']['total'] > 0) { ?>
                                        <?php foreach($debtState['retard']['detail'] as $month => $debt) { ?>
                                            <span class="col-6 p-0"><?= $month ?></span>
                                            <span class="col-6 p-0">Bs. <?= $debt * $usd['price'] ?></span>
                                        <?php } ?>
                                    <?php } else { ?>
                                        SIN DEUDA
                                    <?php } ?>
                                </div>
                            </td>
                            <td class="p-1 border border-black text-<?= $debtState['retard']['total'] > 0 ? 'danger' : 'success' ?>">
                                <div class="d-flex justify-content-center flex-wrap">
                                    <?php if($debtState['retard']['total'] > 0) { ?>
                                        <?php foreach($debtState['retard']['detail'] as $month => $debt) { ?>
                                            <span class="col-6 p-0"><?= $month ?></span>
                                            <span class="col-6 p-0"><?= $debt ?>$</span>
                                        <?php } ?>
                                    <?php } else { ?>
                                        SIN DEUDA
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>




                        
                    <tr>
                        <td class="p-1 border border-black bg-theme text-white fw-bold">FOC</td>
                        <?php if($debtState['foc']) { ?> 
                            <td class="p-1 border border-black text-success" colspan="2">PAGADO</td>
                        <?php } else { ?>
                            <td class="p-1 border border-black text-danger">Bs. <?= $focProduct['price'] * $usd['price'] ?></td>
                            <td class="p-1 border border-black text-danger"><?= $focProduct['price'] ?>$</td>
                        <?php } ?>
                    </tr>
                        <tr>
                            <td class="p-1 border border-black bg-theme text-white fw-bold">TOTAL</td>
                            <?php if($total_debt > 0) { ?>                                
                                <td class="p-1 border border-black fw-bold text-danger">Bs. <?= GetPrettyCiphers($total_debt * $usd['price']) ?></td>
                                <td class="p-1 border border-black fw-bold text-danger"><?= GetPrettyCiphers($total_debt) ?>$</td>
                            <?php } else { ?>
                                <td class="p-1 border border-black fw-bold text-success" colspan="2">SIN DEUDA</td>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php  if($scholarshipped) { ?>
                <div class="row col-12 m-0 p-2 justify-content-center text-success h3" id="scholarship">
                    <?= 'Beca ' . $target_account['scholarship'] . ' ' . $target_account['scholarship_coverage'] . '%' ?>
                </div>
            <?php } ?>
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
                                <td class="p-1 border border-black bg-white text-black"><?= $month ?></td>
                                <td class="p-1 border border-black bg-white">
                                    <i class="fa text-<?= $state['paid'] ? 'success fa-check' : 'danger fa-close' ?>"></i>
                                </td>
                                <td class="p-1 border border-black bg-white">
                                    <i class="fa text-<?= $state['debt'] ? 'success fa-check' : 'danger fa-close' ?>"></i>
                                </td>
                                <td class="p-1 border border-black bg-white">
                                    <i class="fa text-<?= $state['partial'] ? 'success fa-check' : 'danger fa-close' ?>"></i>
                                </td>
                            </tr>                                    
                        <?php } ?>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php
include_once 'common/footer.php'; 
?>
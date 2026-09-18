<?php 
$admitted_user_types = ['Cajero', 'Supervisor', 'Estudiante', 'Super', 'Tecnologia', 'SENIAT'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Auth.php';

if(Auth::UserLevelIn(['Cajero', 'Super'])){
    // Verificamos si la tasa del día de hoy ya está puesta
    include_once '../models/coin_model.php';
    $coin_model = new CoinModel();
    $usd = $coin_model->GetCoinByName('Dólar');
    $coin_date = date('Y-m-d', strtotime($usd['price_created_at']));
    $today = date('Y-m-d');
    $usdUpdated = strtotime($today) === strtotime($coin_date);
    if($usdUpdated === false){
        $error = 'Antes de nada, se requiere que la tasa del dólar esté actualizada al día de hoy';
        header("Location: $base_url/views/forms/update_coin_price.php?error=$error&usd=1");
        exit;
    }
}

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
        $siacad = new SiacadModel();
        $product_model = new ProductModel();
        $invoice_model = new InvoiceModel();
        $coin_model = new CoinModel();
        $account_model = new AccountModel();

        $global_vars = $global_vars_model->GetGlobalVars(true);
        $periods = $siacad->GetPeriodsOfStudent($_SESSION['neocaja_cedula']);
        $currentPeriod = $periods[0];
        $periods = array_reverse($periods);

        $focProduct = $product_model->GetProductByName('FOC');
        $monthStates = [];
        $debtStates = [];

        for($i = count($periods); $i--; $i === -1){
            $period = $periods[$i];
            $monthStates[$period['nombreperiodo']] = $invoice_model->GetAccountState($_SESSION['neocaja_cedula'], $period['idperiodo']);
            $debtStates[$period['nombreperiodo']] = $invoice_model->GetDebtOfAccountOfPeriod($_SESSION['neocaja_cedula'], $period['idperiodo']);
        }
        
        $usd = $coin_model->GetCoinByName('Dólar');
        $coin_date = date('Y-m-d', strtotime($usd['price_created_at']));
        $today = date('Y-m-d');
        $usdUpdated = strtotime($today) === strtotime($coin_date);
        //$total_debt = $debtState['months']['total'] + $debtState['retard']['total'];

        $target_account = $account_model->GetAccountByCedula($_SESSION['neocaja_cedula']);
        $scholarshipped = !($target_account['scholarship'] === NULL && $target_account['scholarship_coverage'] === NULL);

        $total_debt = 0;
        //if($debtState['foc'] === false)
            //$total_debt += $focProduct['price'];
    ?>
    <div class="x_panel row col-12 m-0 p-0 justify-content-center align-items-center pt-2">              
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
                            <td class="border border-black"><?= intval($global_vars['Dia tope mora']) ?></td>
                        </tr>
                        <tr>
                            <td class="bg-theme text-white">Porcentaje de la diferencia de mensualidad</td>
                            <td class="border border-black"><?= intval($global_vars['Porcentaje mora']) ?>%</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="row col-12 m-0 p-0 justify-content-center">

            <div class="row col-12 m-0 p-0 mt-4">
                <div class="row col-12 m-0 p-0 justify-content-center">
                    <?php foreach($periods as $period) { ?>
                        <button 
                            class="text-white btn-period btn btn-<?= $period['nombreperiodo'] === $currentPeriod['nombreperiodo'] ? 'success' : 'secondary' ?> m-0 mx-2 " 
                            title="Ver periodo <?= $period['nombreperiodo'] ?>"
                            id="btn-<?= $period['nombreperiodo'] ?>"
                            style="border-bottom-left-radius: 0px; border-bottom-right-radius: 0px;"
                            onclick="ChangePeriod('<?= $period['nombreperiodo'] ?>')"
                        >
                            <?= $period['nombreperiodo'] ?>
                        </button>
                    <?php } ?>
                </div>

                <?php foreach($periods as $period) { ?>
                    <?php 
                        $debtState = $debtStates[$period['nombreperiodo']]; 
                        $monthState = $monthStates[$period['nombreperiodo']];
                    ?>

                    <div class="row col-12 m-0 p-2 border period-view justify-content-center align-items-start <?= $period['nombreperiodo'] === $currentPeriod['nombreperiodo'] ? '' : 'd-none' ?>" id ="debt-<?= $period['nombreperiodo'] ?>">
                        <h2 class="col-12 text-center h2">
                            Estado de cuenta del periodo <strong><?= $period['nombreperiodo'] ?></strong>
                        </h2>

                        <div class="row col-12 col-lg-6 m-0 p-0">                            
                            <div class="d-flex justify-content-center table-responsive">
                                <?php include 'common/tables/account_debt_table.php'; ?>                    
                            </div>
                        </div>
    
                        <div class="row m-0 p-0 col-12 col-lg-6 justify-content-start">
                            <div class="row m-0 p-0 col-12 justify-content-center" id="invoices">
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
                                        <?php foreach($monthState as $month => $state) { ?>
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
            </div>



            <?php  if($scholarshipped) { ?>
                <div class="row col-12 m-0 p-2 justify-content-center text-success h3" id="scholarship">
                    <?= 'Beca ' . $target_agetElementsByClassNameccount['scholarship'] . ' ' . $target_account['scholarship_coverage'] . '%' ?>
                </div>
            <?php } ?>
        </div>

        
    </div>
<?php } ?>

<script>
    function ChangePeriod(period){
        const containers = document.getElementsByClassName('period-view')
        const buttons = document.getElementsByClassName('btn-period')

        Array.from(containers).forEach((container) => {            
            container.classList.add('d-none')
        })

        Array.from(buttons).forEach((btn) => {            
            btn.classList.add('btn-secondary')
            btn.classList.remove('btn-success')
        })

        document.getElementById('btn-' + period).classList.add('btn-success')
        document.getElementById('debt-' + period).classList.remove('d-none')
    }
</script>

<?php include_once 'common/footer.php'; ?>
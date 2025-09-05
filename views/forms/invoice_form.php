<?php
$admitted_user_types = ['Super', 'Cajero'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';
include_once '../../utils/Auth.php';

include_once '../../models/coin_model.php';
$coin_model = new CoinModel();

$latest = $coin_model->GetCoinByName('Dólar');
$lastRateDate = new DateTime($latest['price_created_at'], new DateTimeZone('America/Caracas'));
$today = new DateTime('now', new DateTimeZone('America/Caracas'));
if($lastRateDate->format('Y-m-d') !== $today->format('Y-m-d')){
    header("Location: $base_url/views/forms/update_coin_price.php?error=Antes de facturar, se requiere que la tasa del dólar esté actualizada al día de hoy");
    exit;
}

include_once '../common/header.php';


include_once '../../models/account_model.php';
include_once '../../models/bank_model.php';
include_once '../../models/sale_point_model.php';
include_once '../../models/payment_method_model.php';
include_once '../../models/product_model.php';
include_once '../../models/siacad_model.php';
include_once '../../models/invoice_model.php';
include_once '../../models/global_vars_model.php';

$account_model = new AccountModel();
$bank_model = new BankModel();
$sale_point_model = new SalePointModel();
$payment_method_model = new PaymentMethodModel();

$product_model = new ProductModel();
$siacad = new SiacadModel();
$invoice_model = new InvoiceModel();
$global_vars_model = new GlobalVarsModel();

$accounts = $account_model->GetAccounts();
$banks = $bank_model->GetActivebanks();
$sale_points = $sale_point_model->GetSalePoints();
$payment_methods = $payment_method_model->GetAllPaymentMethodTypes();
$coins = $coin_model->GetActiveCoins();
$coinHistories = $coin_model->GetOrderedCoinHistories();
$products = $product_model->GetActiveProducts();
$global_vars = $global_vars_model->GetGlobalVars(true);

$period = $siacad->GetCurrentPeriodo();
$periodId = $period['idperiodo'];

$latest = $invoice_model->GetLatestNumbers();

?>

<form 
    action="../../controllers/handle_invoice.php" 
    method="POST"
    class="row justify-content-center"
    onkeydown="return event.key != 'Enter';"
    >
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_invoices_of_today.php'; include_once '../layouts/backButton.php'; ?>
    </div>

    <div class="col-12 justify-content-center px-5 mt-4">
        <div class="d-flex justify-content-around align-items-center x_panel flex-wrap">
            <div class="col-12 text-center">
                <h3 class="h1 text-black">Seleccionar fecha de la tasa</h3>
            </div>
            <div class="col-12 text-center">
                <input id="rate-date" class="form-control mx-auto col-3 fs-1" type="date" name="rate-date" value="<?= date('Y-m-d') ?>">
            </div>

            <div class="col-12 text-center">
                <h3 class="h5 text-black">
                    Click en la moneda para ver las 7 tasas más recientes
                </h3>                
            </div>

            <?php foreach($coins as $coin) { if($coin['name'] === 'Bolívar') continue; ?>
                <table class="table table-bordered col-3">
                    <tr class="text-center align-middle h4">
                        <td id="<?= $coin['name'] ?>" class="bg-theme text-white fw-bold" style="cursor:pointer" onclick="DisplayCoinHistory(this.id)">
                            <?= $coin['name'] ?>
                        </td>
                        <td id="<?= $coin['name'] ?>-rate"><?= $coin['price'] ?></td>
                    </tr>
                </table>
            <?php } ?>
        </div>
    </div>
    <div class="col-12 justify-content-center px-5 mt-4">
        <div class="d-flex justify-content-center align-items-center flex-column  confirm-form">
            <div class="col-12 text-center">
                <h1 class="h1 text-black">Registrar nueva factura</h1>
            </div>

            <div class="row col-12 m-0 p-0 justify-content-center align-items-start pb-2">
                <div class="row col-12 my-2 justify-content-start mb-4">
                    <div class="row col-4">
                        <div class="row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4">
                            <label class="h6 m-0 fw-bold px-2" for="invoice_number">Nº Factura</label>
                        </div>
                        <div class="row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start">
                            <input id="invoice_number" name="invoice_number" class=" form-control col-10 col-md-8" placeholder="Número de factura" required value="<?= $latest['invoice_number'] ?>" type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
                        </div>
                    </div>

                    <div class="row col-4">
                        <div class="row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4">
                            <label class="h6 m-0 fw-bold px-2" for="control_number">Nº Control</label>
                        </div>
                        <div class="row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start">
                            <input id="control_number" name="control_number" class=" form-control col-10 col-md-8" placeholder="Número de control" required value="<?= $latest['control_number'] ?>" type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
                        </div>
                    </div>

                    <div class="row col-4">
                        <div class="row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4">
                            <label class="h6 m-0 fw-bold px-2">Periodo</label>
                        </div>
                        <div class="row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start">
                            <input disabled class=" form-control col-10 col-md-8"  value="<?= $period['nombreperiodo'] ?>" type="text" >
                        </div>
                    </div>
                </div>

                <div class="row col-12 col-md-6 my-2 justify-content-start">
                    <div class="row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-3">
                        <label class="h6 m-0 fw-bold px-2" for="account">Cliente</label>
                    </div>
                    <div class=" row col-12 col-md-9 m-0 p-0 justify-content-center justify-content-md-start ">
                        <div class="row m-0 p-0 col-12 col-md-10">
                            <select id="account" name="account" class="form-control col-10 col-md-8 select2" required>
                                <option value="">&nbsp;</option>
                                <?php foreach($accounts as $account) { ?>
                                    <option value="<?= $account['id'] ?>">
                                        <?= '(' . $account['cedula'] . ') ' . $account['names'] . ' ' . $account['surnames'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                    </div>
                    <div class="row m-0 p-0 col-12 col-md-10 justify-content-center my-2">
                        <a class="d-none" href="<?= $base_url . '/views/detailers/client_details.php?id=' ?>" target="_blank" id="account-link">
                            <button class="btn btn-info" type="button">
                                Ver cliente
                            </button>
                        </a>
                    </div>
                </div>

                <div class="row col-12 col-md-6 my-2 justify-content-start">
                    <div class="x_panel row col-12 m-0 p-2 justify-content-center d-none" id="invoices">
                        <table class="table table-bordered  border border-black">
                            <thead class="text-center">
                                <tr class="h4 m-0 bg-light">
                                    <th class="border border-black" colspan="4">Sus facturas de este periodo</th>
                                </tr>
                                <tr class="h5 m-0">
                                    <th class="border border-black">Nº de Factura</th>
                                    <th class="border border-black">Fecha</th>
                                    <th class="border border-black">Ver</th>
                                </tr>
                            </thead>
                            <tbody id="invoice-table">                        
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="x_panel row col-12 m-0 p-0 justify-content-center align-items-start pt-2 my-2 border-top">
                <div class="col-12 d-flex align-items-center">
                    <h2 class="h2">
                        Productos
                    </h2>
                    <button class="btn btn-info p-2 m-0 d-flex align-items-center" type="button" onclick="AddProduct()" style="margin-left:10px !important;" title="Añadir producto">
                        <i class="fa fa-plus" style="font-size:20px"></i>
                    </button>
                </div>
                <div class="col-12">
                    <table class="col-12 table table-bordered">
                        <thead class="text-center">
                            <tr class="bg-light">
                                <th class="p-1 col-3 align-middle">Producto</th>
                                <th class="p-1 col-2 align-middle">Mes</th>
                                <th class="p-1 align-middle">Completo</th>
                                <th class="p-1 align-middle">Monto base ($)</th>
                                <th class="p-1 align-middle">Descuento de beca</th>
                                <th class="p-1 align-middle">Total</th>
                                <th class="p-1 align-middle">Borrar</th>
                            </tr>
                        </thead>
                        <tbody id="product-table">
                        </tbody>
                        <tr>
                            <td class="text-right h5 fw-bold" colspan="5">Total</td>
                            <td class="text-center fw-bold h4" id="products-total"></td>
                        </tr>
                        <tr>
                            <td class="text-right h5 fw-bold" colspan="5">Total en Bs.</td>
                            <td class="text-center fw-bold h4" id="products-total-bs"></td>
                        </tr>
                    </table>
                </div>
            </div>


            <div class="x_panel row col-12 m-0 p-0 justify-content-center align-items-start pt-2 my-2 border-top">
                <div class="col-12 d-flex align-items-center">
                    <h2 class="h2">
                        Métodos de pago
                    </h2>
                    <button class="btn btn-info p-2 m-0 d-flex align-items-center" type="button" onclick="AddPayment()" style="margin-left:10px !important;" title="Añadir método de pago">
                        <i class="fa fa-plus" style="font-size:20px"></i>
                    </button>
                </div>
                <div class="col-12">
                    <table class="col-12 table table-bordered">
                        <thead class="text-center">
                            <tr class="bg-light">
                                <th class="p-1 align-middle" style="width:11%">Método</th>
                                <th class="p-1 align-middle px-5" style="width:10%">Moneda</th>
                                <th class="p-1 align-middle" style="width:17%">Banco</th>
                                <th class="p-1 align-middle px-5" style="width:13%">Pto de venta</th>
                                <th class="p-1 align-middle px-5" style="width:13%">Nº Doc.</th>
                                <th class="p-1 align-middle" style="width:12%">Monto</th>
                                <th class="p-1 align-middle" style="width:12%">Total</th>
                                <th class="p-1 align-middle" style="width:15%">Borrar</th>
                            </tr>
                        </thead>
                        <tbody id="payment-table">
                        </tbody>
                        <tr>
                            <td class="text-right h5 fw-bold" colspan="6">Total</td>
                            <td class="text-center fw-bold h4" id="payment-total"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="x_panel row col-12 m-0 p-0 justify-content-center align-items-start pt-2 my-2 border-top d-none" id="igtf-table">
                <div class="col-12 d-flex align-items-center">
                    <h2 class="h2">
                        IGTF
                    </h2>
                </div>
                <div class="col-12">
                    <table class="col-12 table table-bordered">
                        <thead class="text-center">
                            <tr class="bg-light">
                                <th class="p-1 align-middle" style="width:11%">Método</th>
                                <th class="p-1 align-middle px-5" style="width:10%">Moneda</th>
                                <th class="p-1 align-middle" style="width:17%">Banco</th>
                                <th class="p-1 align-middle px-5" style="width:13%">Pto de venta</th>
                                <th class="p-1 align-middle px-5" style="width:13%">Nº Doc.</th>
                                <th class="p-1 align-middle" style="width:12%">Monto</th>
                                <th class="p-1 align-middle" style="width:12%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex justify-content-center m-0">
                                        <select class="select2" name="igtf-method" id="igtf-method">
                                            <option value=""></option>
                                            <?php foreach($payment_methods as $payment_method) { ?>
                                                <option value="<?= $payment_method['id'] ?>"><?= $payment_method['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center m-0">
                                        <select class="select2" name="igtf-coin" id="igtf-coin">
                                            <option value=""></option>
                                            <?php foreach($coins as $coin) { ?>
                                                <option value="<?= $coin['id'] ?>"><?= $coin['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center m-0">
                                        <select class="select2" name="igtf-bank" id="igtf-bank">
                                            <option value=""></option>
                                            <?php foreach($banks as $bank) { ?>
                                                <option value="<?= $bank['id'] ?>"><?= $bank['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center m-0">
                                        <select class="select2" name="igtf-salepoint" id="igtf-salepoint">
                                            <option value=""></option>
                                            <?php foreach($sale_points as $sale_point) { ?>
                                                <option value="<?= $sale_point['id'] ?>"><?= $sale_point['code'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </td>

                                <td>
                                    <input class="col-12 form-control" type="text" name="igtf-document" id="igtf-document">
                                </td>

                                <td>
                                    <input class="col-12 form-control" type="text" name="igtf-price" id="igtf-price" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46)" >
                                </td>

                                <td>
                                    <input class="col-12 form-control" type="text" id="igtf-total" disabled>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>


            <div class="row col-12 m-0 p-0 justify-content-around align-items-start pt-4 border-top">
                <div class="row col-6 m-0 p-3">
                    <label class="h6 m-0 fw-bold px-2" for="observation">Observación</label>
                    <textarea 
                    class="col-12 form-control" 
                    rows="3" 
                    maxlength="255" 
                    name="observation" 
                    id="observation"
                    ></textarea>
                </div>
            </div>


            <div class="row col-12 m-0 p-0 justify-content-center mt-5">
                <button type="submit" class="btn btn-success fw-bold">Registrar</button>
            </div>
        </div>
    </div>
</form>

<?php include_once '../common/footer.php'; ?>


<script>
    const rateDate = document.getElementById('rate-date')
    let coinValues = {}

    rateDate.addEventListener('change', async function(e) { await ChangingRateDate(e) })

    async function ChangingRateDate(e){
        if(e.target.value !== '')
            await GetCoinRatesOfDay(e.target.value)
    }


    async function GetCoinRatesOfDay(date){
        var result = await FetchCoinRatesOfDay(date)
            
        if(typeof result !== "string"){
            coinValues = result['data']

            for(let key in coinValues){
                const targetElement = document.getElementById(key + '-rate')
                if(targetElement !== null){
                    targetElement.innerHTML = coinValues[key]
                }
            }

        }

        UpdateProductsPrice()
        RefreshPaymentMethods()
    }

    async function FetchCoinRatesOfDay(date){
        var url = '<?= $base_url ?>/api/get_coin_values_of_date.php?date=' + date

        var fetchConfig = {
            method: 'GET', 
            headers: {
                'Content-Type': 'application/json',
            },
        }

        return await TryFetch(url, fetchConfig)
    }
</script>


<?php include_once '../common/partials/invoice_form_invoice_javascript.php'; ?>
<?php include_once '../common/partials/invoice_form_payments_javascript.php'; ?>
<?php include_once '../common/partials/invoice_form_igtf_javascript.php'; ?>
<?php include_once '../common/partials/invoice_form_months_state_javascript.php'; ?>

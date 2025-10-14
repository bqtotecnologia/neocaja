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
include_once '../../models/unknown_incomes_model.php';

$account_model = new AccountModel();
$bank_model = new BankModel();
$sale_point_model = new SalePointModel();
$payment_method_model = new PaymentMethodModel();
$unknown_model = new UnknownIncomesModel();
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
$incomes = $unknown_model->GetIdentifiedIncomesBetweenDates($period['fechainicio'], $period['fechafin']);

$latest = $invoice_model->GetLatestNumbers();

?>

<form 
    action="../../controllers/handle_invoice.php" 
    method="POST"
    class="row justify-content-center"
    id="invoice_form"
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
        <div class="d-flex justify-content-center align-items-center flex-column">
            <div class="col-12 text-center">
                <h1 class="h1 text-black">Registrar nueva factura</h1>
            </div>

            <div class="x_panel row col-12 m-0 p-0 justify-content-center align-items-center my-2">
                <div class="row col-12 my-2 justify-content-start my-3">
                    <div class="row col-4">
                        <div class="row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4">
                            <label class="h6 m-0 fw-bold px-2" for="invoice_number">Nº Factura</label>
                        </div>
                        <div class="row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start">
                            <input 
                            id="invoice_number" 
                            name="invoice_number" 
                            class=" form-control col-10 col-md-8" 
                            placeholder="Número de factura" 
                            required 
                            readonly
                            value="<?= $latest['invoice_number'] ?>" 
                            type="text" 
                            onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                            onclick="ToggleReadOnly(this)"
                            >
                        </div>
                    </div>

                    <div class="row col-4">
                        <div class="row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4">
                            <label class="h6 m-0 fw-bold px-2" for="control_number">Nº Control</label>
                        </div>
                        <div class="row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start">
                            <input 
                            id="control_number" 
                            name="control_number" 
                            class=" form-control col-10 col-md-8" 
                            placeholder="Número de control" 
                            required 
                            readonly
                            value="<?= $latest['control_number'] ?>" 
                            type="text" 
                            onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                            onclick="ToggleReadOnly(this)"
                            >
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
            </div>

            <div class="x_panel row col-12 m-0 p-0 justify-content-center align-items-center pt-2">              
                <div class="row col-12 my-2 justify-content-start">
                    <div class="row m-0 p-0 col-6 justify-content-center align-items-center">
                        <div class="row col-12 justify-content-center align-items-center">
                            <div class="row m-0 h-100 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-3">
                                <label class="h6 m-0 fw-bold px-2 my-auto" for="account">Cliente</label>
                            </div>

                            <div class="row m-0 p-0 col-12 col-md-8">
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
                        <div class=" row col-12 col-md-9 m-0 p-0 justify-content-center justify-content-md-start ">                           
                            <div class="row m-0 p-0 col-12 justify-content-center my-2">
                                <a class="d-none" href="<?= $base_url . '/views/detailers/client_details.php?id=' ?>" target="_blank" id="account-link">
                                    <button class="btn btn-info" type="button">
                                        Ver cliente
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row m-0 p-0 col-6 justify-content-center align-items-center">
                        <div class="row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-3">
                            <label class="h6 m-0 fw-bold px-2 text-right" for="known-incomes">Ingresos identificados</label>
                        </div>
                        <div class=" row col-12 col-md-9 m-0 p-0 justify-content-center justify-content-md-start ">
                            <div class="row m-0 p-0 col-12 col-md-10">
                                <select id="known-incomes" name="known-incomes" class="form-control col-10 col-md-8 select2">
                                    <option value="">&nbsp;</option>
                                    <?php foreach($incomes as $income) { ?>
                                        <option value="<?= $income['id'] ?>">
                                            <?= '(' . $income['cedula'] . ') ' . $income['names'] . ' ' . $income['surnames'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row col-12 my-2 justify-content-start">
                    <div class="row col-12 col-md-6 my-2 justify-content-start">                    
                        <div class="row m-0 p-0 col-12 justify-content-center my-2 d-none" id="debt-container">
                            <table class="col-10 table table-bordered border border-black text-center h4">
                                <thead>
                                    <tr class="bg-theme text-white fw-bold">
                                        <th>Deuda</th>
                                        <th>Bolívares</th>
                                        <th>Dólares</th>
                                    </tr>
                                </thead>
                                <tbody class="h5" id="debt-table">
                                </tbody>
                            </table>
                        </div>
                    </div>
    
                    <div class="row col-12 col-md-6 my-2 justify-content-start">
                        <div class="row col-12 m-0 p-2 justify-content-center d-none" id="invoices">
                            <table class="table table-bordered border border-black">
                                <thead class="text-center bg-theme text-white">
                                    <tr class="h4 m-0">
                                        <th class="border border-black" colspan="5">Estado de cuenta en este periodo</th>
                                    </tr>
                                    <tr class="h5 m-0">
                                        <th class="border border-black">Mes</th>
                                        <th class="border border-black">Pagado</th>
                                        <th class="border border-black">Moroso</th>
                                        <th class="border border-black">Abonado</th>
                                        <th class="border border-black">Ver</th>
                                    </tr>
                                </thead>
                                <tbody id="invoice-table">                        
                                </tbody>
                            </table>
                        </div>
    
                        <div class="row col-12 m-0 p-2 justify-content-center d-none text-success h3" id="scholarship">
                        </div>
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
                            <tr class="bg-theme text-white">
                                <th class="p-1 col-4 align-middle">Producto</th>
                                <th class="p-1 col-2 align-middle">Mes</th>
                                <th class="p-1 align-middle">Monto base ($)</th>
                                <th class="p-1 align-middle">Total (Bs)</th>
                                <th class="p-1 align-middle">Borrar</th>
                            </tr>
                        </thead>
                        <tbody id="product-table">
                        </tbody>
                        <tr>
                            <td class="text-right h5 fw-bold p-1" colspan="2">Total</td>
                            <td class="text-center fw-bold h4 p-1" id="products-total"></td>
                            <td class="text-center fw-bold h4 p-1" id="products-total-bs"></td>
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
                            <tr class="bg-theme text-white">
                                <th class="p-1 align-middle" style="width:11%">Método</th>
                                <th class="p-1 align-middle px-5" style="width:10%">Moneda</th>
                                <th class="p-1 align-middle px-5" style="width:13%">Pto de venta</th>
                                <th class="p-1 align-middle" style="width:17%">Banco</th>
                                <th class="p-1 align-middle px-5" style="width:13%">Nº Doc.</th>
                                <th class="p-1 align-middle" style="width:12%">Monto</th>
                                <th class="p-1 align-middle" style="width:12%">Total</th>
                                <th class="p-1 align-middle" style="width:15%">Borrar</th>
                            </tr>
                        </thead>
                        <tbody id="payment-table">
                        </tbody>
                        <tr>
                            <td class="text-right h5 fw-bold p-1" colspan="6">Total</td>
                            <td class="text-center fw-bold h4 p-1" id="payment-total"></td>
                        </tr>
                        <tr>
                            <td class="text-right h5 fw-bold p-1" colspan="6">Diferencia</td>
                            <td class="text-center fw-bold h4 p-1" id="payment-diff"></td>
                            <td class="text-center fw-bold h4 p-1" id="payment-diff-usd"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="x_panel row col-12 m-0 p-0 justify-content-center align-items-start pt-2 my-2 border-top d-none" id="igtf-table">
                <div class="col-12 d-flex align-items-center">
                    <h2 class="h2 col-6">
                        IGTF
                    </h2>
                    <h4 class="h4 col-6 text-right text-black fw-bold" id="igtf-total-label">
                    </h4>
                </div>
                <div class="col-12">
                    <table class="col-12 table table-bordered">
                        <thead class="text-center">
                            <tr class="bg-theme text-white">
                                <th class="p-1 align-middle" style="width:11%">Método</th>
                                <th class="p-1 align-middle px-5" style="width:10%">Moneda</th>
                                <th class="p-1 align-middle px-5" style="width:13%">Pto de venta</th>
                                <th class="p-1 align-middle" style="width:17%">Banco</th>
                                <th class="p-1 align-middle px-5" style="width:13%">Nº Doc.</th>
                                <th class="p-1 align-middle" style="width:12%">Monto</th>
                                <th class="p-1 align-middle" style="width:12%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex justify-content-center m-0">
                                        <select class="form-control" name="igtf-method" id="igtf-method" disabled>
                                            <option value=""></option>
                                            <?php foreach($payment_methods as $payment_method) { ?>
                                                <option value="<?= $payment_method['id'] ?>"><?= $payment_method['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center m-0">
                                        <select class="form-control" name="igtf-coin" id="igtf-coin" disabled>
                                            <option value=""></option>
                                            <?php foreach($coins as $coin) { ?>
                                                <option value="<?= $coin['id'] ?>"><?= $coin['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </td>                               
                                <td>
                                    <div class="d-flex justify-content-center m-0">
                                        <select class="form-control" name="igtf-salepoint" id="igtf-salepoint" disabled onchange="IGTFSalePointSelecting(this.value)">
                                            <option value=""></option>
                                            <?php foreach($sale_points as $sale_point) { ?>
                                                <option value="<?= $sale_point['code'] ?>"><?= $sale_point['code'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </td>
                                 <td>
                                    <div class="d-flex justify-content-center m-0">
                                        <select class="select2" name="igtf-bank" id="igtf-bank" disabled>
                                            <option value=""></option>
                                            <?php foreach($banks as $bank) { ?>
                                                <option value="<?= $bank['id'] ?>"><?= $bank['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </td>

                                <td>
                                    <input class="col-12 form-control" type="text" name="igtf-document" id="igtf-document" disabled>
                                </td>

                                <td>
                                    <input class="col-12 form-control" type="text" name="igtf-price" id="igtf-price" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46)"  disabled>
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


            <div class="row col-12 m-0 p-0 justify-content-center my-5">
                <button type="submit" class="btn btn-success fw-bold">Registrar</button>
            </div>
        </div>
    </div>
</form>

<script>
    const myForm = document.getElementById('invoice_form')
    myForm.addEventListener('submit', ValidateForm)

    function ValidateForm(e){
        e.preventDefault()
        var error = ''
        var productTotal = parseFloat(document.getElementById('products-total-bs').innerHTML)
        var paymentTotal = parseFloat(document.getElementById('payment-total').innerHTML)

        var invalidValues = [0, undefined]

        if(
            productTotal  in invalidValues || 
            paymentTotal in invalidValues ||
            isNaN(productTotal) || isNaN(paymentTotal)
        ){
            error = 'Para facturar se necesita al menos un producto y un método de pago'
        }

        
        if(error === ''){
            if(paymentTotal > productTotal)
                error = 'No se puede facturar un monto inferior al total de los métodos de pago'
        }

        if(error === ''){
            // Validamos que haya escogido meses consecutivos empezando por el primero
            var nextMonth = parseInt(youngestPayableMonth)            
            var selectedMonths = []
            var consecutiveMonths = 0
            var cyclesMade = -1
    
            for(let i = 0; i <= nextProduct; i++){
                const input = document.getElementById('product-month-' + i)
                if(input === null)
                    continue

                if(input.value !== '' && !selectedMonths.includes(input.value)){
                    selectedMonths.push(input.value)
                }
            }

            while(true){
                cyclesMade++;
                for(let i = 0; i < selectedMonths.length; i++){
                    currentMonth = selectedMonths[i]
                    if(parseInt(currentMonth) === nextMonth){
                        nextMonth++;
                        if(nextMonth === 13)
                            nextMonth = 1
                        
                        consecutiveMonths++
                        break;
                    }
    
                }
    
                if(cyclesMade > selectedMonths.length)
                    break;
            }
    
            if(selectedMonths.length !== consecutiveMonths)
                error = 'Debes seleccionar meses consecutivos y empezar por el primero';
        }

        if(error === ''){
            Swal.fire({
            title: "Confirmación",
            html: "¿Desea crear la factura?",
            showCancelButton: true,
            confirmButtonText: "Proceder",
            denyButtonText: 'Cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    myForm.submit()
                }
            });
        }
        else{
            Swal.fire({
                title: "Error",
                icon:'error',
                html: error,
            })            
        }

    }
</script>

<?php include_once '../common/footer.php'; ?>

<?php include_once '../common/partials/invoice_form/initializations.php'; ?>
<?php include_once '../common/partials/invoice_form/fetchs.php'; ?>
<?php include_once '../common/partials/invoice_form/element_builder.php'; ?>

<?php include_once '../common/partials/invoice_form/account_module.php'; ?>
<?php include_once '../common/partials/invoice_form/coin_module.php'; ?>
<?php include_once '../common/partials/invoice_form/igtf_module.php'; ?>
<?php include_once '../common/partials/invoice_form/invoice_module.php'; ?>
<?php include_once '../common/partials/invoice_form/payment_methods_module.php'; ?>
<?php include_once '../common/partials/invoice_form/products_module.php'; ?>
<?php include_once '../common/partials/invoice_form/utils_functions.php'; ?>

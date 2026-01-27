<?php
$admitted_user_types = ['Estudiante'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/Auth.php';

include_once '../../models/coin_model.php';
$coin_model = new CoinModel();

$latest = $coin_model->GetCoinByName('Dólar');
$lastRateDate = new DateTime($latest['price_created_at'], new DateTimeZone('America/Caracas'));
$today = new DateTime('now', new DateTimeZone('America/Caracas'));
if($lastRateDate->format('Y-m-d') !== $today->format('Y-m-d')){
    header("Location: $base_url/views/panel.php?error=La tasa del dólar no ha sido actualizada todavia. Intentelo más tarde");
    exit;
}

include_once '../common/header.php';

include_once '../../models/product_model.php';
include_once '../../models/siacad_model.php';
include_once '../../models/shop_model.php';
include_once '../../models/global_vars_model.php';
include_once '../../models/transfers_model.php';
include_once '../../models/mobile_payments_model.php';

$product_model = new ProductModel();
$siacad = new SiacadModel();
$shop_model = new ShopModel();
$global_vars_model = new GlobalVarsModel();
$transfers_model = new TransfersModel();
$mobile_payments_model = new MobilePaymentsModel();

$current_period = $siacad->GetCurrentPeriodo();

$usdValue = $coin_model->GetCoinByName('Dólar');
$products = $product_model->GetAvailableProductsOfStudent($_SESSION['neocaja_cedula'], $current_period['idperiodo']);
    
$global_vars = $global_vars_model->GetGlobalVars(true);
$transfers = $transfers_model->GetActiveTransfers();
$mobile_payments = $mobile_payments_model->GetActiveMobilePayments();

?>

<main class="row col-12 m-0 p-0 justify-content-center align-items-start animated">

    <section class="x_panel row m-0 p-0 col-12 text-center mb-3">
        <div class="col-12 m-0 p-2" >
            <h1 class="h1">Registrar pago remoto</h1>
        </div>
    </section>

    <div class="x_panel row col-12 m-0 p-2 animated">
        <section class="row col-12 m-0 p-1 justify-content-center align-items-start my-hidden" id="checkout-container">
            <div class="col-12 text-center">
                <h1 class="col-12 h1">Resumen de transacción</h1>
            </div>

            <div class="row col-12 m-0 p-0 justify-content-center">
                <table class="col-12 col-lg-6 h6 text-black">
                    <thead>
                        <tr class="bg-theme text-white text-center fw-bold h5">
                            <th class="p-1 py-2">Producto</th>
                            <th class="p-1 py-2">Precio ($)</th>
                            <th class="p-1 py-2">Total Bs.</th>
                        </tr>
                    </thead>
                    <tbody id="checkout-cart-table">
                    </tbody>
                    <tfoot>                        
                        <tr class="fw-bold text-right bg-theme text-white h5">
                            <td class="p-1">Total:</td>
                            <td class="p-1" id="checkout-total-usd"></td>
                            <td class="p-1" id="checkout-total-bs"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row m-0 p-0 col-12 text-center justify-content-center mt-3">
                <table class="col-10 col-lg-6">
                    <tr>
                        <td class="bg-theme fw-bold p-1 text-white h2">TOTAL A PAGAR</td>
                        <td class="p-1 h2 fw-bold text-black" id="total-to-pay"></td>
                    </tr>
                </table>
            </div>

            <div class="row m-0 p-0 col-12 text-center justify-content-center align-items-start mt-3">
                <div class="col-12 text-center mt-3">
                    <h2 class="col-12 h2">Escoger método de pago</h2>
                </div>

                <div class="row m-0 p-0 col-12 text-center mt-3 justify-content-center">
                    <select class="form-control col-12 col-lg-5" id="payment-method-type">
                        <option value="">¿Cómo desea pagar?</option>
                        <option value="mobile_payment">Pago móvil</option>
                        <option value="transfer">Transferencia</option>
                    </select>
                </div>                

                 <div class="row m-0 p-0 col-12 text-center mt-3 d-none justify-content-center" id="payment-method-container">
                    <select class="form-control col-12 col-lg-5" id="payment-method">
                    </select>
                </div>

                <div class="row m-0 p-0 col-12 text-center mt-5 justify-content-center h2 text-black text-center" id="payment-method-header">
                </div>

                <div class="row m-0 p-0 col-12 text-center my-3 d-none justify-content-center" id="payment-method-display">
                    <table class="col-lg-5 table text-center h5" id="payment-method-table">                        
                    </table>
                </div>        



                <div class="row m-0 p-0 col-12 col-lg-5 my-3 d-none" id="checkout-inputs-container">
                    <div class="row col-12 m-0 p-0 align-items-center my-1">
                        <h3 class="col-12 text-center h3">Tras realizar la transacción registre los siguientes datos</h3>
                    </div>
                    <div class="row col-12 m-0 p-0 align-items-center my-1">
                        <label class="col-12 col-lg-4 text-right align-middle m-0 h6" for="cedula">Cédula del emisor</label>
                        <input class="col-12 col-lg-8 form-control" id="cedula" type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
                    </div>

                    <div class="row col-12 m-0 p-0 align-items-center my-1">
                        <label class="col-12 col-lg-4 text-right align-middle m-0 h6" for="ref">Referencia de la transacción</label>
                        <input class="col-12 col-lg-8 form-control" id="ref" type="text" placeholder="Referencia completa" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
                    </div>

                    <div class="row col-12 m-0 p-0 align-items-center my-1">
                        <label class="col-12 col-lg-4 text-right align-middle m-0 h6" for="price">Monto de la transacción</label>
                        <input class="col-12 col-lg-8 form-control" id="price" type="text" placeholder="Usar punto en vez de coma" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46">
                    </div>

                    <div class="row col-12 m-0 p-0 align-items-center mt-5">
                        <button class="btn btn-success m-0 mx-auto" onclick="RegisterPayment()">
                            Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section class="row col-12 col-lg-6 col-xl-8 m-0 p-1 justify-content-center align-items-start animated" id="product-container">
            <div class="col-12 text-center border border-black rounded">
                <h3 class="h3 border-bottom mb-5">Seleccione los conceptos que desea pagar</h3>                

                <div class="row m-0 p-0 my-3 col-12 justify-content-start align-items-start flex-wrap">
                    <?php foreach($products as $product) { ?>
                        <article class="row m-0 p-2 col-6 col-xl-3 align-self-stretch">
                            <div class="w-100 border border-black rounded product-card text-black p-1" id="<?= $product['code'] ?>" onclick="SelectProduct(this)">
                                <h6 class="h5 fw-bold col-12 text-center p-0"><?= $product['name'] ?></h6>
                                <p class="h5 fw-bold w-100 text-center m-0"><?= $product['price'] ?>$</p>
                            </div>
                        </article>
                    <?php } ?>
                </div>
            </div>
        </section>

        <section class="row col-12 col-lg-6 col-xl-4 m-0 mt-4 mt-lg-0 p-1 justify-content-center align-items-start animated" id="cart-container">
            <div class="row col-12 justify-content-center align-items-start">
                <table class="w-100 h6 text-black animated">
                    <thead>
                        <tr class="bg-theme text-white text-center fw-bold h5">
                            <th class="p-1 py-2">Producto</th>
                            <th class="p-1 py-2">Precio ($)</th>
                            <th class="p-1 py-2">Total Bs.</th>
                        </tr>
                    </thead>
                    <tbody id="cart-table">
                    </tbody>
                    <tfoot>                        
                        <tr class="fw-bold text-right bg-theme text-white h5">
                            <td class="p-1">Total:</td>
                            <td class="p-1" id="total-usd">0</td>
                            <td class="p-1" id="total-bs">0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>

        <section class="row col-12 justify-content-center align-items-center pt-4 animated" id="proceed-container">
            <button class="btn btn-success m-0" onclick="ConfirmProceed()">
                Proceder
            </button>
        </section>
    </div>
</main>

<?php include_once '../common/footer.php'; ?>

<?php include_once '../common/partials/pay_form/initializations.php'; ?>
<?php include_once '../common/partials/pay_form/utils_functions.php'; ?>

<?php include_once '../common/partials/pay_form/element_builder.php'; ?>
<?php include_once '../common/partials/pay_form/fetchs.php'; ?>
<?php include_once '../common/partials/pay_form/product_module.php'; ?>
<?php include_once '../common/partials/pay_form/checkout_module.php'; ?>


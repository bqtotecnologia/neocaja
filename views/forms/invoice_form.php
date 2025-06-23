<?php
$admitted_user_types = ['Super', 'Cajero'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

include_once '../common/header.php';

include_once '../../models/account_model.php';
include_once '../../models/bank_model.php';
include_once '../../models/sale_point_model.php';
include_once '../../models/payment_method_model.php';
include_once '../../models/coin_model.php';
include_once '../../models/product_model.php';

$account_model = new AccountModel();
$bank_model = new BankModel();
$sale_point_model = new SalePointModel();
$payment_method_model = new PaymentMethodModel();
$coin_model = new CoinModel();
$product_model = new ProductModel();

$accounts = $account_model->GetAccounts();
$banks = $bank_model->GetActivebanks();
$sale_points = $sale_point_model->GetSalePoints();
$payment_methods = $payment_method_model->GetAllPaymentMethodTypes();
$coins = $coin_model->GetActiveCoins();
$products = $product_model->GetActiveProducts();

// onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46)"

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_invoices_of_today.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <form action="../../controllers/handle_invoice.php" method="POST" id="" class="d-flex justify-content-center align-items-center flex-column x_panel confirm-form ">
            <div class="col-12 text-center">
                <h1 class="h1 text-black">Registrar nueva factura</h1>
            </div>

            <div class=" row col-12 col-md-8 my-2 justify-content-start ">
                <div class=" row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4 ">
                    <label class=" h6 m-0 fw-bold px-2 " for="account">Cliente</label>
                </div>
                <div class=" row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start ">
                    <div class="row m-0 p-0 col-12 col-md-8">
                        <select id="account" name="account" class="form-control col-10 col-md-8 select2">
                            <option value="">&nbsp;</option>
                            <?php foreach($accounts as $account) { ?>
                                <option value="<?= $account['id'] ?>">
                                    <?= '(' . $account['cedula'] . ') ' . $account['names'] . ' ' . $account['surnames'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>


            <div class=" row col-12 col-md-8 my-2 justify-content-start p-2 bg-primary">
            </div>

        


            <div class=" row col-12 col-md-8 my-2 justify-content-start ">
                <div class=" row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4 ">
                    <label class=" h6 m-0 fw-bold px-2 " for="cedula">Cliente</label>
                </div>
                <div class=" row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start ">
                    <input id="cedula" name="cedula" class=" form-control  col-10 col-md-5" placeholder="" required="" value="" type="number"  maxlength="10" minlength="7">
                </div>
            </div>

            <div class=" row col-12 col-md-8 my-2 justify-content-start ">
                <div class=" row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4 ">
                    <label class=" h6 m-0 fw-bold px-2 " for="names">Nombres</label>
                </div>
                <div class=" row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start ">
                    <input id="names" name="names" class=" form-control  col-10 col-md-8" placeholder="Ambos nombres" required="" value="" type="text" maxlength="100" minlength="5">
                </div>
            </div>

            <div class=" row col-12 col-md-8 my-2 justify-content-start ">
                <div class=" row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4 ">
                    <label class=" h6 m-0 fw-bold px-2 " for="address">Dirección</label>
                </div>
                <div class=" row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start ">
                    <textarea id="address" name="address" class=" form-control  col-10 col-md-8" required="" rows="3" columns="50" value=""
                    ></textarea> 
                </div>
            </div>

            <div class=" row col-12 col-md-8 my-2 justify-content-start ">
                <div class=" row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4 ">
                    <label class=" h6 m-0 fw-bold px-2 " for="is_student">Es estudiante</label>
                </div>
                <div class=" row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start ">
                    <div class="row col-12 m-0 p-0 mx-2">
                        <div class="icheckbox_flat-green" style="position: relative;">
                            <input id="is_student-1" type="checkbox" name="is_student[]" value="1" class=" form-control  col-10 col-md-5 flat ">
                        </div>
                        <label for="is_student-1" class=" text-left mx-2 h6 ">Es estudiante</label>
                    </div>
                </div>
            </div>

            <div class=" row col-12 col-md-8 my-2 justify-content-start ">
                <div class=" row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4 ">
                    <label class=" h6 m-0 fw-bold px-2 " for="company">Empresa</label>
                </div>
                <div class=" row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start ">
                    <div class="row m-0 p-0 col-12 col-md-8">
                        <select id="company" name="company" class="form-control  col-10 col-md-8 select2  select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                            <option value="">&nbsp;</option>
                            <option value="1">Granel SA</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row col-12 m-0 p-0 justify-content-center">
                <button type="submit" class="btn btn-success">Registrar</button>
            </div>
        </form>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>
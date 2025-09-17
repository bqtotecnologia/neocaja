<?php
$admitted_user_types = ['Super', 'Estudiante'];
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

include_once '../../models/product_model.php';
include_once '../../models/siacad_model.php';
include_once '../../models/shop_model.php';
include_once '../../models/global_vars_model.php';

$product_model = new ProductModel();
$siacad = new SiacadModel();
$shop_model = new ShopModel();
$global_vars_model = new GlobalVarsModel();

$coins = $coin_model->GetActiveCoins();
$products = $product_model->GetActiveProducts();
$global_vars = $global_vars_model->GetGlobalVars(true);

?>

<main class="row col-12 m-0 p-0 justify-content-center align-items-start">
    <div class="x_panel row col-12 m-0 p-2">
        <section class="col-12 text-center">
            <h1 class="h1">Registrar pago remoto</h1>
        </section>

        <section class="row col-12 col-lg-9 m-0 p-1 justify-content-center align-items-start" id="product-container">
            <div class="col-12 text-center border border-black">
                <h3 class="h3 border-bottom">Seleccione los conceptos que desea pagar</h3>
            </div>
            <div class="row m-0 p-0 my-3 col-12 justify-content-start align-items-start flex-wrap">
                <?php foreach($products as $product) { ?>
                    <article class="row m-0 p-2 col-4 col-lg-3">
                        <div class="w-100 border border-black rounded product-card text-black p-1">
                            <h6 class="h6 col-12 text-center"><?= $product['name'] ?></h6>
                            <p class="w-100 text-center"><?= $product['price'] ?>$</p>
                        </div>
                    </article>
                <?php } ?>
            </div>
        </section>

        <section class="row col-12 col-lg-9 m-0 p-1 justify-content-center align-items-start" id="cart-container">

        </section>
    </div>
</main>


<?php include_once '../common/footer.php'; ?>

<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';

include_once '../../models/coin_model.php';
$coin_model = new CoinModel();
$coins = $coin_model->GetAllCoins();

$display_coins = [];
$usdId = null;
foreach($coins as $coin){
    if($coin['name'] === 'Dólar')
        $usdId = $coin['id'];

    $to_add = ['display' => $coin['name'], 'value' => $coin['id']];
    array_push($display_coins, $to_add);
}

$focusUSD = false;
if(isset($_GET['usd'])){
    $focusUSD = true;
}

if(isset($_GET['id'])){
    include_once '../../utils/Validator.php';

    $error = '';
    $id = Validator::ValidateRecievedId();
    if(is_string($id))
        $error = $id;

    if($error === ''){
        $target_coin = $coin_model->GetCoin($id);
        if($target_coin === false)
            $error = 'Moneda no encontrada';
    }

    if($error !== ''){
        header("Location: $base_url/views/tables/search_coin.php?error=$error");
        exit;
    }
}

include_once '../common/header.php';


$form = true;
include_once '../../fields_config/coins_price.php';
include_once '../../utils/FormBuilder.php';
$formBuilder = new FormBuilder(
    '../../controllers/update_coin_price.php',    
    'POST',
    'Actualizar la tasa de una moneda manualmente',
    'Actualizar',
    '',
    $coinPriceFields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_coin.php'; include_once '../layouts/backButton.php'; ?>
    </div>

    <div class="col-12 row m-0 p-0 justify-content-center mt-5">
        <h4 class="text-danger text-center col-12">
            Si la tasa ya existe en la fecha dada se actualizará, sino, se creará la tasa de ese día.
        </h4>
        <h5 class="text-center col-12">
            <a href="https://www.bcv.org.ve/" target="_blank">Página del banco central</a>
        </h5>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>

<?php if($focusUSD) { ?>
    <script>
        $('#coin').val('<?= $usdId ?>')
    </script>
<?php } ?>
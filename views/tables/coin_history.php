<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/Validator.php';

include '../../views/common/header.php';

$id = Validator::ValidateRecievedId();
if(is_string($id)){
    header("Location: " . $base_url . "/views/tables/search_coin.php?error=$id");
    exit;
}

include_once '../../models/coin_model.php';
$coin_model = new CoinModel();
$target_coin = $coin_model->GetCoin($id);

if($target_coin === false){
    header('Location: ' . $base_url . '/views/tables/search_coin.php?error=Moneda no encontrado');
    exit;
}

$coin_history = $coin_model->GetCoinHistory($target_coin['id']);
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Historial de tasas de la moneda "<?= $target_coin['name'] ?>"</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <?php 
            $btn_url = 'search_coin.php'; 
            include_once '../layouts/backButton.php';
            include '../common/tables/coin_history_table.php'; 
            ?>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
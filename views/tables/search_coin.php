<?php 
$admitted_user_types = ['Cajero', 'Tecnologia', 'Super'];
include_once '../../utils/validate_user_type.php';

include '../../views/common/header.php';
include_once '../../models/coin_model.php';
$coin_model = new CoinModel();

$coins = $coin_model->GetAllCoins();
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Lista de las monedas</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <?php 
            if(Auth::UserLevelIn(['Tecnologia', 'Super'])){
                $btn_url = '../forms/coin_form.php'; 
                include_once '../layouts/addButton.php';
            }
            ?>
            <div class="table-responsive">
                <?php include '../common/tables/coin_table.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
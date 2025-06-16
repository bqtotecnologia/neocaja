<?php
$admitted_user_types = ['Tecnología', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';

include_once '../../models/coin_model.php';

$coin_model = new CoinModel();
$coins = $coin_model->GetAllCoins();

$display_coins = [];
foreach($coins as $coin){
    $to_add = ['display' => $coin['name'], 'value' => $coin['id']];
    array_push($display_coins, $to_add);
}

$fields = [
    [
        'name' => 'coin',
        'display' => 'Moneda',
        'placeholder' => '',
        'id' => 'coin',
        'type' => 'select',
        'size' => 4,
        'max' => 11,
        'min' => 1,
        'required' => true,
        'value' => isset($_GET['id']) ? $_GET['id'] : '',
        'elements' => $display_coins
    ],
    [
        'name' => 'price',
        'display' => 'Tasa',
        'placeholder' => '',
        'id' => 'price',
        'type' => 'decimal',
        'size' => 4,
        'required' => true,
        'value' => '0',
    ],
    [
        'name' => 'date',
        'display' => 'Fecha',
        'placeholder' => '',
        'id' => 'date',
        'type' => 'date',
        'size' => 4,
        'required' => true,
        'value' => date('Y-m-d'),
        'max' => date('Y-m-d'),
    ],
];

$formBuilder = new FormBuilder(
    '../../controllers/update_coin_price.php',    
    'POST',
    'Actualizar la tasa de una moneda manualmente',
    'Actualizar',
    '',
    $fields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_coin.php'; include_once '../layouts/backButton.php'; ?>
    </div>

    <div class="col-12 row m-0 p-0 justify-content-center mt-5">
        <h4 class="text-danger">
            Si la tasa ya existe en la fecha dada se actualizará, sino, se creará la tasa de ese día.
        </h4>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>
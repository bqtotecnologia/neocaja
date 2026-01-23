<?php
$edit = isset($_GET['id']);
if($edit)
    $admitted_user_types = ['Super', 'Cajero'];
else
    $admitted_user_types = ['Super', 'Tecnologia'];

include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';


if($edit){
    include_once '../../utils/Validator.php';
    $id = Validator::ValidateRecievedId();

    if(is_string($id)){
        header("Location: $base_url/views/tables/search_coin.php?error=$id");
        exit;
    }   

    include_once '../../models/coin_model.php';
    $coin_model = new CoinModel();
    $target_coin = $coin_model->GetCoin($id);
    if($target_coin === false){
        header("Location: $base_url/views/tables/search_coin.php?error=Moneda no encontrada");
        exit;
    }
}

include_once '../common/header.php';

$form = true;
include_once '../../fields_config/coins.php';
include_once '../../utils/FormBuilder.php';



$formBuilder = new FormBuilder(
    '../../controllers/handle_coin.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nueva') . ' moneda',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $coinFields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_coin.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php if($edit) { ?>
    <script>
        document.getElementById('name').readOnly = true
    </script>
<?php } ?>


<?php include_once '../common/footer.php'; ?>
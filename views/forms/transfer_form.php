<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';

$edit = isset($_GET['id']);
$form = true;

$error = '';
if($edit){
    include_once '../../utils/Validator.php';
    $id = Validator::ValidateRecievedId();

    if(is_string($id))
        $error = $id;
}

if($error === '' && $edit){
    include_once '../../models/transfers_model.php';
    $transfers_model = new TransfersModel();
    $target_transfer = $transfers_model->GetTransfer($id);
    if($transfers_model === false)
        $error = 'Transferencia no encontrada';
}

if($error !== ''){
    header("Location: $base_url/views/tables/search_transfers.php?error=$error");
    exit;
}

$rif_letters = ['V', 'J', 'E', 'P', 'G'];
$display_rif_letters = [];
foreach($rif_letters as $letter){
    array_push($display_rif_letters,
    [
        'display' => $letter,
        'value' => $letter
    ]);
}

include_once '../../models/bank_model.php';
$bank_model = new BankModel();
$banks = $bank_model->GetActivebanks();


$display_banks = [];
foreach($banks as $bank){
    array_push($display_banks,
    [
        'display' => $bank['name'],
        'value' => $bank['id']
    ]);
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';
include_once '../../fields_config/transfers.php';

$formBuilder = new FormBuilder(
    '../../controllers/handle_transfer.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nueva') . ' cuenta bancaria para transferencias',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $transferFields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_transfers.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>
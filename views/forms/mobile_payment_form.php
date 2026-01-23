<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';


$edit = isset($_GET['id']);
$form = true;

$error = '';
if($edit){
    include_once '../../utils/Validator.php';
    $id = Validator::ValidateRecievedId();

    if(is_string($id)){
        $error = $id;
    }   
}

if($error === '' && $edit){
    include_once '../../models/mobile_payments_model.php';
    $mobile_payment_model = new MobilePaymentsModel();
    $target_mobile_payment = $mobile_payment_model->GetMobilePayment($id);
    if($mobile_payment_model === false)
        $error = 'Pago móvil no encontrado';
}

if($error !== ''){
    header("Location: $base_url/views/tables/search_mobile_payments.php?$error");
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
include_once '../../fields_config/mobile_payments.php';
include_once '../../utils/FormBuilder.php';


$formBuilder = new FormBuilder(
    '../../controllers/handle_mobile_payment.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nuevo') . ' pago móvil',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $mobilePaymentFields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_mobile_payments.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>
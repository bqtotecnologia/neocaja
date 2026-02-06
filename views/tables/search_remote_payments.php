<?php 
$admitted_user_types = ['Cajero', 'Super', 'SENIAT'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/Validator.php';

include_once '../../models/account_payments_model.php';
$payment_model = new AccountPaymentsModel();

$error = '';
if(isset($_GET['state'])){

    if(Validator::HasSuspiciousCharacters($_GET['state']))
        $error = 'El estado contiene caracteres sospechosos';
}
else{
    $error = 'No se recibió ningún criterio de búsqueda';    
}

if($error !== ''){
    header("Location: $base_url/views/panel.php?error=$error");
    exit;
}

$payments = $payment_model->GetPaymentsOfState($_GET['state']);

include_once '../../utils/prettyCiphers.php';
include '../../views/common/header.php';    

?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Pagos remotos <?= $_GET['state'] ?></h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">            
            <?php $btn_url = "$base_url/views/panel.php"; include_once '../layouts/backButton.php'; ?>
            <div class="table-responsive">
                <?php include '../common/tables/account_payments_table.php';  ?>
            </div>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
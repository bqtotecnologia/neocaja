<?php 
$admitted_user_types = ['Estudiante', 'Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

include_once '../../models/account_model.php';
$account_model = new AccountModel();


$target_date = false;
if(!empty($_POST)) {
    $error = '';
    try{
        $target_date = new DateTime($_POST['date']);
    }
    catch(Exception $e){
        $error = 'Fecha inválida';
    }

    if($error !== ''){
        header("Location: $base_url/views/panel.php?error=$error");
        exit;
    }
}

include '../../views/common/header.php';

?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <?php if($target_date === false) { ?>
            <h1 class="h1 text-black">Buscar pagos remotos por fecha</h1>
        <?php } else { ?>
            <h1 class="h1 text-black">Pagos remotos de <?= $target_date->format('d/m/Y') ?></h1>
        <?php } ?>
    </div>    
    <div class="col-12 row justify-content-center px-4">
        <form class="col-12 row justify-content-center x_panel" method="POST" action="<?= $base_url ?>/views/tables/search_remote_payments_by_date.php">
            <div class="row col-12 m-0 p-0 my-2">
                <div class="col-3 col-md-5 text-right align-middle d-flex align-items-center justify-content-end p-0">
                    <label class="fw-bold h6" for="fecha_nacimiento">
                        Fecha:
                    </label>
                </div>
                <div class="col-8 col-md-3 px-2">
                    <input class="form-control" type="date" name="date" value="<?php if($target_date !== false) { echo $target_date->format('Y-m-d'); } ?>">
                </div>
            </div>

            <div class="row col-12 m-0 p-0 my-2 justify-content-center py-2">
                <button class="btn btn-success">
                    Buscar
                </button>
            </div>
        </form>
    </div>

    <?php if($target_date !== false) { ?>
        <?php
        include_once '../../models/account_payments_model.php';
        include_once '../../utils/prettyCiphers.php';
        $payment_model = new AccountPaymentsModel();
        $payments = $payment_model->GetPaymentsOfDate($target_date->format('Y-m-d'));
        ?>
        <div class="col-12 row justify-content-center px-4">
            <div class="col-12 row justify-content-center x_panel">
                <?php 
                include '../common/tables/account_payments_table.php';
                ?>
            </div>
        </div>
    <?php } ?>
</div>

<?php include '../common/footer.php'; ?>
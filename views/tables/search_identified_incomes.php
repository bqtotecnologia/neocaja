<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';

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
            <h1 class="h1 text-black">Buscar ingresos identificados por fecha</h1>
        <?php } else { ?>
            <h1 class="h1 text-black">Ingresos identificados del <?= $target_date->format('d/m/Y') ?></h1>
        <?php } ?>
    </div>    
    <div class="col-12 row justify-content-center px-4">
        <form class="col-12 row justify-content-center x_panel" method="POST" action="<?= $base_url ?>/views/tables/search_identified_incomes.php">
            <div class="row col-12 m-0 p-0 my-2">
                <div class="col-3 col-md-5 text-right align-middle d-flex align-items-center justify-content-end p-0">
                    <label class="fw-bold h6" for="date">
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
            include_once '../../models/unknown_incomes_model.php';
            $unknown_model = new UnknownIncomesModel();
            $incomes = $unknown_model->GetUnknownIncomesByDate($target_date->format('Y-m-d'), true);
        ?>
        <div class="col-12 row justify-content-center px-4">
            <div class="col-12 row justify-content-center x_panel">
                <div class="table-responsive">
                    <?php include '../common/tables/unknown_incomes_table.php'; ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php include '../common/footer.php'; ?>
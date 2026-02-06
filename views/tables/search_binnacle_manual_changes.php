<?php
$admitted_user_types = ['Super', 'Supervisor', 'SENIAT'];
include_once '../../utils/validate_user_type.php';

include_once '../common/header.php';

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../panel.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <?php 
        include_once '../../models/binnacle_model.php';
        $binnacle_model = new BinnacleModel();
        $binnacle = $binnacle_model->GetManualChanges();
    ?>
    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <div class="table-responsive">
                <?php include '../common/tables/binnacle_table.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>
<?php 
$admitted_user_types = ['Cajero', 'Super', 'Administrador'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/months_data.php';

include '../../views/common/header.php';
include_once '../../models/period_range_model.php';
include_once '../../models/siacad_model.php';

$period_range_model = new PeriodRangeModel();
$siacad = new SiacadModel();

$period_ranges = $period_range_model->GetPeriodRanges();
$ids = [];

foreach($period_ranges as $range){
    array_push($ids, $range['period']);
}

$periods = $siacad->GetPeriodsByIdList($ids, true);
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Rangos de meses de los periodos</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <?php $btn_url = '../forms/period_range_form.php'; include_once '../layouts/addButton.php'; ?>
            <div class="table-responsive">
                <?php include '../common/tables/period_range_table.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
<?php 
$admitted_user_types = ['Cajero', 'Super', 'SENIAT', 'Administrador'];
include_once '../../utils/validate_user_type.php';

include '../../views/common/header.php';

include_once '../../models/absolution_model.php';
include_once '../../models/siacad_model.php';

$absolution_model = new AbsolutionModel();
$siacad = new SiacadModel();

$absolutions = $absolution_model->GetAbsolutions();

$periodNames = $siacad->GetperiodsAsDict();
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Lista de los clientes absueltos</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <?php if($_SESSION['neocaja_rol'] !== 'SENIAT') { ?>
                <?php $btn_url = '../forms/absolution_form.php'; include_once '../layouts/addButton.php'; ?>
            <?php } ?>
            <div class="table-responsive">
                <?php include '../common/tables/absolution_table.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
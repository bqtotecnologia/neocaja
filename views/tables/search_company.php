<?php 
$admitted_user_types = ['Cajero', 'Super', 'SENIAT'];
include_once '../../utils/validate_user_type.php';

include '../../views/common/header.php';

include_once '../../models/company_model.php';
$company_model = new CompanyModel();
$companies = $company_model->GetCompanies();
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Lista de las empresas</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <?php if($_SESSION['neocaja_rol'] !== 'SENIAT') { ?>
                <?php $btn_url = '../forms/company_form.php'; include_once '../layouts/addButton.php'; ?>
            <?php } ?>
            <div class="table-responsive">
                <?php include '../common/tables/company_table.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
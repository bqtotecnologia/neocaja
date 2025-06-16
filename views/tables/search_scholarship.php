<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

include '../../views/common/header.php';
include_once '../../models/scholarship_model.php';
$scholarship_model = new ScholarshipModel();

$scholarships = $scholarship_model->GetScholarships();
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Lista de las becas</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <?php 
            $btn_url = '../forms/scholarship_form.php'; 
            include_once '../layouts/addButton.php';
            include '../common/tables/scholarship_table.php';
            ?>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
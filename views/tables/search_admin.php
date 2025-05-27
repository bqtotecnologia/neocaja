<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

include '../../views/common/header.php';
include_once '../../models/admin_model.php';
$admin_model = new AdminModel();

$admins = $admin_model->GetAdmins();
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Lista de los administradores</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <?php $btn_url = '../forms/admin_form.php'; include_once '../layouts/addButton.php' ?>
            <?php include '../common/tables/admins_table.php'; ?>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
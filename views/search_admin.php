

<?php 
session_start();
// Si el usuario NO es del tipo correcto, lo mandamos al index y cerramos la sesión
if($_SESSION['eva_tipo'] !== 'super'){
    session_destroy();
    header('Location:../index.php');
    exit;
}

include 'common/header.php';
include_once '../models/admin_model.php';
$admin_model = new AdminModel();
$admins = $admin_model->GetAdmins();
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Lista de los administradores</h1>
    </div>

    <?php if(isset($_GET['message'])) { ?>
    <div class="alert alert-success alert-dismissible h6" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <?= $_GET['message'] ?>
    </div>
    <?php } ?>

    
    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <div class="col-12  x_title">
                <a href="add_admin.php" class="btn btn-app">
                    <i class="fa fa-plus" style="color:green"></i> Agregar
                </a>
            </div>
            <?php include 'common/admins_table.php'; ?>
        </div>
    </div>
</div>

<?php include 'common/footer.php'; ?>
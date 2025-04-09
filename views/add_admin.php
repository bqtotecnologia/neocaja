<?php
session_start();
// Si el usuario NO es un super administrador, lo mandamos al index y cerramos la sesión
if($_SESSION['neocaja_tipo'] !== 'super'){
    session_destroy();
    header('Location:../index.php');
    exit;
}

include 'common/header.php';
include_once '../models/admin_model.php';
$admin_model = new AdminModel();
$admins = $admin_model->GetAdmins();

$edit = false;
if(isset($_GET['edit'])){
    if($_GET['edit'] === '1'){
        $edit = true;
        $target_admin = $admin_model->GetAdminById($_GET['id']);
    }
}

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <div class="col-12 x_title">
            <a href="search_admin.php" class="btn btn-app">
                <i class="fa fa-arrow-left text-success"></i> Ver listado
            </a>
        </div>
    </div>
    <div class="col-12 text-center">
        <h1 class="h1 text-black">
        <?php if($edit) echo 'Editar '; else echo 'Registrar nuevo '; ?> administrador
        </h1>
    </div>

    <?php if(isset($_GET['error'])) { ?>
    <div class="alert alert-danger alert-dismissible h6" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <?= $_GET['error'] ?>
    </div>

    <?php } ?>

    <div class="col-12 justify-content-center px-5">
        <form 
        action="../controllers/handle_admin.php" 
        method="POST" 
        id="demo-form2" 
        data-parsley-validate 
        class="form-horizontal form-label-left d-flex justify-content-center align-items-center flex-column x_panel">
            <script>
                function timeFunctionLong(input) {
                    setTimeout(function() {
                        input.type = 'text';
                    }, 60000);
                }
            </script>

            <table class="col-12 col-lg-8 mt-4">
                <tr>
                    <td class="text-right py-3 w-25 px-2">
                        <label class="fw-bold h6 m-0" for="cedula">
                            Cedula:
                        </label>
                    </td>
                    <td>
                        <input maxlength="100" type="text" id="cedula" name="cedula" required="required" class="form-control w-75"
                        value="<?php if($edit === true) echo $target_admin['cedula']; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="text-right py-3 w-25 px-2">
                        <label class="fw-bold h6 m-0" for="nivel">
                            Tipo:
                        </label>
                    </td>
                    <td>
                        <select class="form-control w-75" required name="nivel">
                            <option value="admin" <?php if($edit === true) { if($target_admin['nivel'] === 'admin') echo 'selected'; } ?> >Administrador</option>
                            <option value="coord" <?php if($edit === true) { if($target_admin['nivel'] === 'coord') echo 'selected'; } ?> >Coordinador</option>
                        </select>
                    </td>
                </tr>
                <?php if($edit) { ?>
                    <input type="hidden" name="id" required="required" class=""
                    value="<?= $target_admin['id'] ?>">
                    <input type="hidden" name="edit" required="required" class="" value="1">
                <?php } ?>
                <tr>
                    <td colspan="2">
                        <div class="row col-12 text-center justify-content-center p-0">
                            <div class="d-flex col-12 justify-content-center">
                                <button type="submit" class="d-flex btn btn-success m-0">
                                    <?php if($edit) echo 'Editar'; else echo 'Registrar'; ?>
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<style>
    td, th{
        border: none;
    }
</style>
<?php include 'common/footer.php'; ?>
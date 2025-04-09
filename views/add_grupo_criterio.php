<?php
session_start();
// Si el usuario NO es del tipo correcto, lo mandamos al index y cerramos la sesión
if(!in_array($_SESSION['eva_tipo'], ['admin', 'super'])){
    session_destroy();
    header('Location: ../index.php');
    exit;
}

include 'common/header.php'; 
include_once '../models/criterio_model.php';
$criterio_model = new CriterioModel();

$edit = false;
if(isset($_GET['edit']) && isset($_GET['id'])){
    if($_GET['edit'] === '1') $edit = true;
    if($_GET['id'] !== '')
        $grupo_criterio = $criterio_model->GetGrupoCriterioById($_GET['id']);
}

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <div class="col-12 x_title">
            <a href="evaluation_element_list.php?element=grupo_criterio" class="btn btn-app">
                <i class="fa fa-arrow-left text-success"></i> Ver listado
            </a>
        </div>
    </div>
    <div class="col-12 text-center">
        <h1 class="h1 text-black">
            <?php if($edit) echo 'Editar '; else echo 'Registrar nuevo '; ?> grupo criterio
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
        action="../controllers/handle_grupo_criterio.php" 
        method="POST" 
        id="criterio-form" 
        data-parsley-validate 
        class="form-horizontal form-label-left d-flex justify-content-center align-items-center flex-column x_panel">
            <?php if($edit === true) { ?>
                <input type="hidden" name="element_id" value="<?= $_GET['id'] ?>">
            <?php } ?>
            <div class="col-12 row justify-content-center">
                <div class="col-12 row justify-content-center">
                    <div class="col-2 text-right py-1 my-auto">
                        <label class="fw-bold h6 m-0 my-auto" for="element_name">
                            Grupo criterio:
                        </label>
                    </div>
                    <div class="col-8 mb-3">
                        <textarea 
                        maxlength="300" 
                        cols="30"
                        rows="3"
                        id="element_name" 
                        name="element_name" 
                        required 
                        class="form-control"
                        placeholder="Evitar los siguientes caracteres: < > / \\ ; { } [ ] $ & | ¿ ? ! - = ' + &quot;"
                        ><?php if($edit === true) echo $grupo_criterio['nombre']; ?></textarea>
                    </div>
                </div>

                <?php if($edit) { ?>
                    <input maxlength="20" type="hidden" name="edit" required value="1">
                <?php } ?>
            
            </div>

            <div class="ln_solid"></div>
            <div>
                <h3 class="text-danger" id="error-displayer"></h3>
            </div>
            <div class="item form-group col-12 text-center">
                <div class="col-md-6 col-sm-6 offset-md-3">
                    <button type="submit" class="btn btn-success">
                        <?php if($edit) echo 'Editar'; else echo 'Registrar'; ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include_once 'common/footer.php'; ?>
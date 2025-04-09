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
$criterio = null;

$edit = false;
if(isset($_GET['edit']) && isset($_GET['id'])){
    if($_GET['edit'] === '1') $edit = true;
    if($_GET['id'] !== ''){
        $criterio = $criterio_model->GetCriterioById($_GET['id']);
    }
}
$type_translator = [
    'student' => 'Estudiantes',
    'teacher' => 'Docentes',
    'coord' => 'Coordinadores'
];

$dimensiones = $criterio_model->GetDimensiones();
$grupos_criterios = $criterio_model->GetGruposCriterios();

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <div class="col-12 x_title">
            <a href="evaluation_element_list.php?element=criterio" class="btn btn-app">
                <i class="fa fa-arrow-left text-success"></i> Ver listado
            </a>
        </div>
    </div>
    <div class="col-12 text-center">
        <h1 class="h1 text-black">
            <?php if($edit) echo 'Editar '; else echo 'Registrar nuevo '; ?> criterio
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
        action="../controllers/handle_criterio.php" 
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
                            Criterio:
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
                        ><?php if($edit === true) echo $criterio['nombre']; ?></textarea>
                    </div>
                </div>

                <div class="col-12 row justify-content-center">
                    <div class="col-2 text-right py-1 my-auto">
                        <label class="fw-bold h6 m-0 my-auto" for="grupo_criterio">
                            Tipo de criterio:
                        </label>
                    </div>
                    <div class="col-8 mb-3">
                        <select name="grupo_criterio" id="select2" class="select2">
                            <option value=""></option>
                            <?php foreach($grupos_criterios as $grupo_criterio) { ?>
                                <option value="<?= $grupo_criterio['id'] ?>"
                                <?php if($grupo_criterio['id'] === $criterio['grupo_criterio']) echo 'selected' ?>
                                >
                                    <?= $grupo_criterio['nombre'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="col-12 row justify-content-center">
                    <div class="col-2 text-right py-1 my-auto">
                        <label class="fw-bold h6 m-0 my-auto" for="peso">
                            Peso:
                        </label>
                    </div>
                    <div class="col-8 mb-3">
                        <input class="form-control w-25" name="peso" type="number" required value="<?php if($edit === true) echo $criterio['peso']; else echo '0'; ?>">
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

<script src="../build/js/elementHandlerValidator.js"></script>
<?php include_once 'common/footer.php'; ?>
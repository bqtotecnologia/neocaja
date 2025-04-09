<?php
    header('Location:../index.php');
    exit;
    include_once 'common/header.php';

    if(!in_array($_SESSION['eva_tipo'], ['super', 'admin'])){
        session_destroy();
        header('Location:../index.php');
        exit;
    }

    include_once '../models/docentes_periodos_model.php';
    $docentes_periodos = new DocentesPeriodosModel();
    $periodos = $docentes_periodos->GetEvaluatedPeriodos();
?>

<div class="row justify-content-around text-center">
    <div class="col-12 row m-0 p-2">
        <h1 class="col-12 m-0 p-0 h1">
            Seleccione un periodo para ver las observaciones
        </h1>
    </div>

    <?php if(isset($_GET['error'])) { ?>
        <h3 class="text-center col-12 mb-4 fw-bold h3 text-danger">
            <?= $errors[$_GET['error']] ?>
        </h3>
    <?php } ?>
    <?php if(isset($_GET['message'])) { ?>
        <h3 class="text-center col-12 mb-4 fw-bold h3 text-success">
            <?= $messages[$_GET['message']] ?>
        </h3>
    <?php } ?>

    <div class="col-12 row m-0 p-2 justify-content-center">
        <?php if($periodos === []) { ?>
            <h3 class="col-6 text-danger">No se han realizado evaluaciones</h3>
        <?php } else { ?>
            <form class="row justify-content-center m-0 p-0 col-8 col-md-3" method="POST">
                <div class="col-12 justify-content-center m-0 p-0 select2-container">
                    <select class="select2 py-1 h6" id="select2" name="periodo" required>
                        <?php foreach($periodos as $periodo) { ?>
                            <option class="h6" value="<?= $periodo ?>"
                                <?php if(isset($_POST['periodo'])) { if($_POST['periodo'] == $periodo) echo ' selected'; } ?>>
                                <?= $periodo ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="m-0 my-2 col-12 d-flex justify-content-center align-items-center">
                    <button class="btn btn-success p-2 px-3" type="submit">Ver observaciones</button>
                </div>
            </form>
        <?php } ?>
    </div>

    <?php // if(isset($_POST['periodo'])) { ?>
    <?php if(false) { ?>
        <div class="col-12 row m-0 p-2 justify-content-around align-items-start">
            <?php if($_POST['periodo'] === '') { ?>
                <h3 class="col-6 text-danger">Escoja a un periodo por favor</h3>
            <?php } else { ?>
                <?php
                    include_once '../models/evaluacion_model.php';
                    include_once '../models/siacad_model.php';

                    $evaluacion_model = new EvaluacionModel();
                    $siacad = new SiacadModel();
                    $observaciones = $evaluacion_model->GetObservacionesOfPeriodo($_POST['periodo']);
                ?>
                <div class="col-12 row justify-content-center px-4">
                    <div class="col-12 row justify-content-center x_panel">
                        <?php include 'common/reporte_observaciones_table.php'; ?>
                    </div>
                </div>

            <?php } ?>
        </div>
    <?php } ?>
</div>


<?php include 'common/footer.php'; ?>
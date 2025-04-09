<?php 
include_once 'common/header.php';

if(empty($_GET)){
    header('Location: panel.php?error=4');
    exit;
}

if(
    !isset($_GET['docente']) ||
    !isset($_GET['periodo'])
){
    header('Location: panel.php?error=4');
    exit;
}

if(
    $_GET['docente'] === '' ||
    $_GET['periodo'] === ''
){
    header('Location: panel.php?error=4');
    exit;
}

include_once '../models/siacad_model.php';
$siacad = new SiacadModel();
$docente = $siacad->GetDocenteById($_GET['docente']);

if($docente === false){
    header('Location: panel.php?error=5');
    exit;
}

include_once '../models/evaluacion_model.php';
$evaluacion_model = new EvaluacionModel();
$corte = isset($_GET['corte']) ? $_GET['corte'] : null;
$observaciones = $evaluacion_model->GetObservacionesOfDocenteIn($_GET['periodo'], $corte, $docente['iddocente']);

?>
<div class="col-12 row justify-content-center">
    <div class="col-12 row p-2">
        <div class="col-2">
            <form action="search_docente.php" method="POST">
                <input type="hidden" name="docente" value="<?= $docente['iddocente'] ?>">
                <button class="btn btn-info">
                    Volver
                </button>
            </form>
        </div>
    </div>
    <h1 class="col-12 text-center my-2">
        Observaciones realizadas al 
        <strong>
        Prof. <?= $docente['nombres'] . ' ' . $docente['apellidos'] ?>
        </strong>
        por parte de todo el instituto.
        <br>
        <?php if($corte !== null) { ?>
            Corte <strong> <?= $corte ?> </strong>
        <?php } ?>
            Periodo <strong> <?= $siacad->GetPeriodoById($_GET['periodo'])['nombreperiodo'] ?> </strong>
    </h1>

    <div class="col-12 row m-0 p-0">
        <div class="col-12 x_panel d-flex jsutify-content-center">
            <?php if($observaciones === false) { ?>
                <h2 class="col-12 h2 text-center text-danger">
                    El docente no tiene observaciones
                </h2>
            <?php } else { ?>
                <table class="table-secondary col-10 p-0" id="datatable">
                    <thead>
                        <tr>
                            <th class="fw-bold h5 text-center">Nº</th>
                            <th class="fw-bold h5 text-center">Observación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count = 1; ?>
                        <?php foreach($observaciones as $observacion) { ?>
                            <tr>
                                <td class="text-center h6">
                                    <?= $count ?>
                                </td>
                                <td class="h6">
                                    <?= $observacion['observacion'] ?>
                                </td>
                            </tr>
                            <?php $count++; ?>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</div>

<?php include_once 'common/footer.php'; ?>
<?php 
session_start();
// Si el usuario NO es del tipo correcto, lo mandamos al index y cerramos la sesión
if(!in_array($_SESSION['eva_tipo'], ['admin', 'super', 'coord'])){
    session_destroy();
    header('Location: ../index.php');
    exit;
}

if(!isset($_GET['docente']) || !isset($_GET['periodo'])){
    header('Location: search_docente.php?error=GET vacío');
    exit;
}

if($_GET['docente'] === '' || $_GET['periodo'] === ''){
    header('Location: search_docente.php?error=Información vacía');
    exit;
}

$corte = isset($_GET['corte']) ? $_GET['corte'] : null;
$iddocente = $_GET['docente'];
$idperiodo = $_GET['periodo'];

include_once '../models/siacad_model.php';
$siacad = new SiacadModel();
$target_docente = $siacad->GetDocenteById($iddocente);

if($target_docente === false){
    header('Location: search_docente.php?error=Docente no encontrado');
    exit;
}

include_once '../models/evaluacion_model.php';
$evaluacion_model = new EvaluacionModel();

$evaluaciones_by_secciones = $evaluacion_model->GetEvaluacionesBySecciones($iddocente, $corte, $idperiodo);

include_once 'common/header.php';

?>

<div class="row w-100 m-0 p-0 justify-content-center">
    <?php if($evaluaciones_by_secciones === false) { ?>
        <h2 class="h2">El docente no ha sido evaluado</h2>
    <?php } else { ?>
        <div class="row col-12 text-center">
            <h2 class="h2 col-12">
                Evaluaciones realizadas al 
                <strong>
                    Prof. <?= $target_docente['nombres'] . ' ' . $target_docente['apellidos'] ?>
                </strong>
                divididas por sección
            </h2>
            <div class="col-12 row m-0 p-0 justify-content-center mt-5">
                <table class="table-secondary col-11 col-md-8 col-lg-6">
                    <thead>
                        <tr class="h4">
                            <th>Sección</th>
                            <th>Evaluaciones</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($evaluaciones_by_secciones as $seccion) { ?>
                            <tr>
                                <td><?= $seccion['semestre'] . ' ' . $seccion['seccion'] . ' ' . $seccion['carrera'] ?></td>
                                <td><?= $seccion['cantidad'] ?></td>
                                <td>
                                    <a href="evaluaciones_of_seccion.php?docente=<?= $iddocente ?>&corte=<?= $corte ?>&periodo=<?= $idperiodo ?>&seccion=<?= $seccion['id_seccion'] ?>">
                                        <button class="btn btn-primary m-1">
                                            Resumen
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php } ?>
</div>

<?php include_once 'common/footer.php'; ?>
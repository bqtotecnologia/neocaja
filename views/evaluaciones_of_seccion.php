<?php 
session_start();
// Si el usuario NO es del tipo correcto, lo mandamos al index y cerramos la sesión
if(!in_array($_SESSION['eva_tipo'], ['admin', 'super', 'coord'])){
    session_destroy();
    header('Location: ../index.php');
    exit;
}

if(!isset($_GET['docente']) || !isset($_GET['periodo']) || !isset($_GET['seccion'])){
    header('Location: search_docente.php?error=GET vacío');
    exit;
}

if($_GET['docente'] === '' || $_GET['periodo'] === '' || $_GET['seccion'] === ''){
    header('Location: search_docente.php?error=Información vacía');
    exit;
}

$corte = $_GET['corte'] !== '' ? $_GET['corte'] : null;
$iddocente = $_GET['docente'];
$idperiodo = $_GET['periodo'];
$id_seccion = $_GET['seccion'];

include_once '../models/siacad_model.php';
$siacad = new SiacadModel();
$target_docente = $siacad->GetDocenteById($iddocente);

if($target_docente === false){
    header('Location: search_docente.php?error=Docente no encontrado');
    exit;
}

include_once '../models/evaluacion_model.php';
include_once '../models/estadistica_docente_model.php';
$evaluacion_model = new EvaluacionModel();
$estadistica_model = new EstadisticaDocenteModel();

$cedulas = $siacad->GetEstudiantesOfSeccion($id_seccion);
$ordered_cedulas = '';

foreach($cedulas as $cedula){
    $ordered_cedulas .= "'" . $cedula['cedula'] . "', " ;
}

$ordered_cedulas = trim($ordered_cedulas, ', ');

$data = array(
    'corte' => $corte,
    'periodo' => $idperiodo,
    'docente' => $iddocente,
    'tipo' => 'student',
    'cedulas' => $ordered_cedulas
);

$categorias = $estadistica_model->GetTotalsOfDocenteByListOfCedulas($data);
$evaluation_number = $evaluacion_model->GetEvaluacionNumberOfDocente($data);
$max = $estadistica_model->GetMaximoPosibleOfDocenteOf($idperiodo, $corte, $iddocente, 'student');
$seccion_name = $siacad->GetSectionName($id_seccion);

include_once 'common/header.php';
?>

<div class="row col-12 m-0 p-2 justify-content-center" id="export_as_pdf">
    <?php if($categorias === []) { ?>
        <h1 class="w-100 text-center h1 text-danger">Aún no ha sido evaluado</h1>
        <div class="w-100 d-flex justify-content-center">
            <a href="search_docente.php">
                <button class="btn btn-info">Volver</button>
            </a>
        </div>
    <?php } else { ?>
        <div class="row m-0 p-0 justify-content-center">
            <div class="col-12 row m-0 p-0 align-items-center print-hidden">
                <figure class="col-4">
                    <img class="w-100" src="../images/iujo-transparent.png" alt="iujo-logo">
                </figure>
                <div class="col-12">
                    <h2 class="h2 m-0 fw-bold text-center">REPORTE DE EVALUACIÓN DE DESEMPEÑO DOCENTE</h2>
                </div>
            </div>
            <h2 class="col-12 text-center my-2 h2 mt-3">
                Evaluaciones realizadas al 
                <strong>
                    Prof. <?= $target_docente['nombres'] . ' ' . $target_docente['apellidos'] ?> 
                </strong>                
                por parte de los estudiantes de 
                <strong>
                    <?= $seccion_name ?>.
                </strong>
                <br>
                <?php if($corte !== null) { ?>
                    Corte <strong> <?= $corte ?> </strong>
                <?php } ?>
                    Periodo <strong> <?= $siacad->GetPeriodoById($_GET['periodo'])['nombreperiodo'] ?> </strong>
            </h2>    
        </div>

        <?php 
        include_once 'common/students_report_displayer.php';
        include_once 'common/evaluation_tail.php';        
        ?>

    <?php } ?>
</div> 

<?php include_once 'common/footer.php'; ?>
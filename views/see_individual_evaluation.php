<?php 
session_start();

if(!isset($_SESSION['eva_user_id']) || !isset($_SESSION['eva_tipo'])){
    session_destroy();
    header('Location: login.php');
    exit;
}

if(!isset($_GET['id'])){
    header('Location: my_evaluations.php?error=0');
    exit;
}

if($_GET['id'] === ''){
    header('Location: my_evaluations.php?error=1');
    exit;
}

include_once '../models/evaluacion_model.php';
$evaluacion_model = new EvaluacionModel();
$target_evaluation = $evaluacion_model->GetEvaluacionById($_GET['id']);

if($target_evaluation === false){
    header('Location: my_evaluations.php?error=2');
    exit;
}
else{
    if($target_evaluation['cedula_evaluador'] !== $_SESSION['eva_cedula']){
        header('Location: my_evaluations.php?error=3');
        exit;
    }
}

include_once '../models/siacad_model.php';
include_once '../models/docentes_periodos_model.php';
$siacad = new SiacadModel();
$docentes_model = new DocentesPeriodosModel();

$docente_periodo = $docentes_model->GetDocentesPeriodosById($target_evaluation['docente_periodo']);
$target_docente = $siacad->GetDocenteById($docente_periodo['iddocente']);

$categorias = $evaluacion_model->GetTotalsOfEvaluacion($target_evaluation['id']);
$evaluation_number = 1;
$max = $target_evaluation['maximo_posible'];

include_once 'common/header.php';
?>

<div class="row justify-content-center" id="export_as_pdf">
    <div class="col-12 p-3 no-print">
        <a href="my_evaluations.php">
            <button class="btn btn-info">Volver</button>
        </a>
    </div>
    <div class="col-12 row m-0 p-0 align-items-center print-hidden">
        <figure class="col-4">
            <img class="w-100" src="../images/iujo-transparent.png" alt="iujo-logo">
        </figure>
        <div class="col-12">
            <h2 class="h2 m-0 fw-bold text-center">REPORTE DE EVALUACIÓN DE DESEMPEÑO DOCENTE</h2>
        </div>
    </div>
    <h2 class="col-12 text-center my-2 h2 mt-3">
        Evaluación realizada por 
        <strong style="text-transform:uppercase">
            Prof. <?= $_SESSION['eva_name'] . ' ' . $_SESSION['eva_surname'] ?>
        </strong>
        a
        <strong>
            Prof. <?= $target_docente['nombres'] . ' ' . $target_docente['apellidos'] ?> 
        </strong>
        <br>
        Corte <strong> <?= $docente_periodo['corte'] ?> </strong>
        Periodo <strong> <?= $siacad->GetPeriodoById($docente_periodo['periodo'])['nombreperiodo'] ?> </strong>
    </h2>

    <div class="row col-12 m-0 p-2">
        <?php if($categorias === false) { ?>
            <h1 class="w-100 text-center h1 text-danger">Aún no ha sido evaluado</h1>
            <div class="w-100 d-flex justify-content-center">
                <a href="my_evaluations.php">
                    <button class="btn btn-info">Volver</button>
                </a>
            </div>
        <?php } else { ?>
            <?php 
                if($target_evaluation['tipo'] === 'student')
                    include_once 'common/student_report_displayer.php';
                else
                    include_once 'common/teacher_report_displayer.php';
            ?>
        <?php } ?>
    </div>
    <?php 
    include_once 'common/evaluation_tail.php';
    include_once 'common/footer.php'; 
    ?>
</div>
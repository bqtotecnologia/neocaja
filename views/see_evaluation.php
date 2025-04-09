<?php 
include_once 'common/header.php';

if(empty($_GET)){
    header('Location: panel.php?error=4');
    exit;
}

if(
    !isset($_GET['docente']) ||
    !isset($_GET['periodo']) ||
    !isset($_GET['tipo']) ||
    !isset($_GET['show_as'])
){
    header('Location: panel.php?error=4');
    exit;
}

if(
    $_GET['docente'] === '' ||
    $_GET['periodo'] === '' ||
    $_GET['tipo'] === '' ||
    $_GET['show_as'] === ''
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

$autoevaluation = false;
if($docente['iddocente'] === $_SESSION['eva_user_id'] &&
    $_SESSION['eva_tipo'] === $_GET['tipo'])
    $autoevaluation = true;

$corte = isset($_GET['corte']) ? $_GET['corte'] : null;
$evaluation_types = array(
    'student' => 'estudiantes',
    'teacher' => 'docentes',
    'coord' => 'coordinadores'
);

?>
<div class="col-12 row justify-content-center export_as_pdf">
    <div class="col-12 p-3">
        <form action="search_docente.php" method="POST">
            <input type="hidden" name="docente" value="<?= $docente['iddocente'] ?>">
            <input type="hidden" name="periodo" value="<?= $_GET['periodo'] ?>">
            <button class="btn btn-info">Volver</button>
        </form>
    </div>
    <div class="col-12 row m-0 p-0" id="export_as_pdf" name="to_export">
        <div class="col-12 row m-0 p-0 align-items-center print-hidden">
            <figure class="col-4">
                <img class="w-100" src="../images/iujo-transparent.png" alt="iujo-logo">
            </figure>
            <div class="col-12">
                <h2 class="h2 m-0 fw-bold text-center">REPORTE DE EVALUACIÓN DE DESEMPEÑO DOCENTE</h2>
            </div>
        </div>
        <h2 class="col-12 text-center my-2 h2 mt-3">
            <?php if($autoevaluation) { ?>
                Autoevaluación de 
            <?php } else { ?>
                Evaluaciones realizadas al 
                <?php } ?>
            <strong>
                Prof. <?= $docente['nombres'] . ' ' . $docente['apellidos'] ?> 
            </strong>
            <?php if(!$autoevaluation) { ?>
                por parte de los 
                <?= $evaluation_types[$_GET['tipo']] ?>.
            <?php } ?>
            <br>
            <?php if($corte !== null) { ?>
                Corte <strong> <?= $corte ?> </strong>
            <?php } ?>
                Periodo <strong> <?= $siacad->GetPeriodoById($_GET['periodo'])['nombreperiodo'] ?> </strong>
        </h2>

        <!-- Para los gráficos -->
        <script src="../vendors/highcharts/highcharts.js"></script>

        <div class="col-12 row justify-content-center mt-5">
            <?php
            include_once '../models/estadistica_docente_model.php';
            include_once '../models/evaluacion_model.php';
            $evaluacion_model = new EvaluacionModel();
            $estadistica_model = new EstadisticaDocenteModel();
            $data = array(
                'corte' => $corte,
                'periodo' => $_GET['periodo'],
                'docente' => $_GET['docente'],
                'tipo' => $_GET['tipo']
            );

            if($autoevaluation){
                if($data['tipo'] === 'teacher')
                    $data['tipo'] = 'coord';
                else if($data['tipo'] === 'coord')
                    $data['tipo'] = 'teacher';
            }
                

            $categorias = $estadistica_model->GetTotalsOfDocente($data);
            $evaluation_number = $evaluacion_model->GetEvaluacionesNumberOfDocenteOf($_GET['periodo'], $data['corte'], $docente['iddocente'], $_GET['tipo']);
            $max = $estadistica_model->GetMaximoPosibleOfDocenteOf($_GET['periodo'], $data['corte'], $docente['iddocente'], $_GET['tipo']);
            ?>

            <div class="row col-12 m-0 p-2">
                <?php if($categorias === false) { ?>
                    <h1 class="w-100 text-center h1 text-danger">Aún no ha sido evaluado</h1>
                    <div class="w-100 d-flex justify-content-center">
                        <a href="search_docente.php">
                            <button class="btn btn-info">Volver</button>
                        </a>
                    </div>
                <?php } else { ?>
                    <?php 
                        if($_GET['tipo'] === 'student') { 
                            include_once 'common/students_' . $_GET['show_as'] . '_displayer.php';
                        }else{
                            include_once 'common/teacher_' . $_GET['show_as'] . '_displayer.php';
                        }
                    ?>
                <?php } ?>
            </div> 
            
            <?php 
            if($categorias !== false)
                include_once 'common/evaluation_tail.php'; 
            ?>
            
        </div>
    </div>
</div>
<?php include_once 'common/footer.php'; ?>
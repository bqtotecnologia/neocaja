<?php 
session_start();
if(!in_array($_SESSION['neocaja_tipo'], ['admin', 'super'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
include 'common/header.php';


$errors = [
    'El docente no llega por POST',
    'Usted ya ha evaluado a ese coordinador',
    'Usted ya ha evaluado a ese docente',
    'Datos recibidos inválidos',
    'Docente no encontrado'
];

$messages = [
    'Usted ha evaluado a todos sus docentes',
    'Usted ha evaluado a todos sus coordinadores'
];

include_once '../models/evaluacion_model.php';
$evaluacion_model = new EvaluacionModel();
$periodos = $evaluacion_model->GetPeriodosOfEvaluaciones();

?>

<div class="row justify-content-center">
    <?php if (isset($_GET['error'])) { ?>
        <div class="col-12 mb-5">
            <h2 class="col-12 h3 text-center text-danger"><?= $errors[$_GET['error']] ?></h2>
        </div>
    <?php } ?>
    <?php if (isset($_GET['message'])) { ?>
        <div class="col-12 mb-5">
            <h2 class="col-12 h3 text-center text-success"><?= $messages[$_GET['message']] ?></h2>
        </div>
    <?php } ?>
    <div class="row col-12 my-3 p-0">
        <div class="col-12">
            <h1 class="w-100 text-center h1">
                Escoja un periodo para ver el resumen de evaluaciones de este
            </h1>
        </div>
    </div>

    <div class="col-12 row m-0 p-2 justify-content-center">
            <?php if($periodos === [] || $periodos === false) { ?>
                <h3 class="col-6 text-danger">No hay periodos registrados</h3>
            <?php } else { ?>
                <form class="row justify-content-center m-0 p-0 col-8 col-md-3" method="POST">
                    <div class="col-12 justify-content-center m-0 p-0 select2-container">
                        <select class="select2 py-1 h6" id="select2" name="periodo" required>
                            <?php foreach($periodos as $periodo) { ?>
                                <option class="h6" value="<?= $periodo['idperiodo'] ?>"
                                    <?php if(isset($_POST['periodo'])) { if($_POST['periodo'] == $periodo['idperiodo']) echo ' selected'; } ?>>
                                    <?= ucfirst($periodo['nombreperiodo']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="m-0 my-2 col-12 d-flex justify-content-center align-items-center">
                        <button class="btn btn-success p-2 px-3" type="submit">Ver evaluaciones</button>
                    </div>
                </form>
            <?php } ?>
        </div>

    <?php 
    if(!empty($_POST)){
        if(isset($_POST['periodo'])){
            $target_periodo = $_POST['periodo'];
            include_once 'common/periodo_summary.php'; 
        }
    }
    ?>
</div>

<?php include 'common/footer.php'; ?>
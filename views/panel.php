<?php include 'common/header.php'; ?>

<?php 
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

include_once '../models/siacad_model.php';
$siacad = new SiacadModel();
$current_periodo = $siacad->GetCurrentPeriodo();

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
                Resumen del periodo <?= $current_periodo['nombreperiodo'] ?>
            </h1>
        </div>
    </div>
    <?php $target_periodo = $current_periodo['idperiodo']; include_once 'common/periodo_summary.php'; ?>
</div>

<?php include 'common/footer.php'; ?>
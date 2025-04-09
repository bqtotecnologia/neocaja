<?php 
include 'common/header.php';

include_once '../models/evaluacion_model.php';
$evaluacion_model = new EvaluacionModel();
$my_evaluations = $evaluacion_model->GetMyEvaluations($_SESSION['neocaja_user_id']);

$errors = [
    'GET vacío',
    'Id vacío',
    'Evaluación no encontrada',
    'La evaluación no es suya',
    'El docente no existe'
];

?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">
            Historial de evaluaciones realizadas
        </h1>
    </div>

    <?php if(isset($_GET['error'])) { ?>
        <div class="col-12 text-center">
            <h1 class="h1 text-danger">
                <?= $errors[$_GET['error']] ?>
            </h1>
        </div>
    <?php } ?>
    
    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
        <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th class="fw-bold text-center">Nº</th>
                    <th class="fw-bold text-center">Nombre</th>
                    <th class="fw-bold text-center">Periodo</th>
                    <th class="fw-bold text-center">Corte</th>
                    <th class="fw-bold text-center">Fecha</th>
                    <th class="fw-bold text-center">Total</th>
                    <th class="fw-bold text-center">Ver</th>
                </tr>
            </thead>

            <tbody>
                <?php $count = 1; ?>
                <?php foreach($my_evaluations as $evaluation) {  ?>
                    <tr class="text-center">
                        <td class="h6 align-middle"><?php echo $count; $count++; ?></td>
                        <td class="h6 align-middle"><?= $evaluation['nombre'] ?></td>
                        <td class="h6 align-middle"><?= $evaluation['periodo'] ?></td>
                        <td class="h6 align-middle"><?= $evaluation['corte'] ?></td>
                        <td class="h6 align-middle">
                            <?= date('d/m/Y', strtotime($evaluation['fecha'])) ?>
                        </td>
                        <td class="h6 align-middle">
                            <?= round($evaluation['total'], 1) ?>/20
                        </td>
                        <td class="h6 align-middle">
                            <div class="col-12 row justify-content-center">
                                <div class="col-3 text-center">
                                    <a href="see_individual_evaluation.php?id=<?= $evaluation['id'] ?>" class="btn btn-success">
                                        <i class="fa fa-search"></i>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<?php include 'common/footer.php'; ?>
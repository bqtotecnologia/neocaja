<?php if($_POST['docente'] === ''){ ?>
    <h1>
        Por favor escoja un docente
    </h1>
<?php } else { ?>
    <?php
    $id_docente = $_POST['docente'];
    $docente = $siacad->GetDocenteById($id_docente);
    ?>

    <?php if($docente === false) { ?>
        <h3 class="text-danger h3">
            El docente que usted está buscando no existe
        </h3>
    <?php } else { ?>
        <?php 
        $coordinacionMatchs = $siacad->CoordinacionMatch($_SESSION['neocaja_user_id'], $docente['iddocente']);
        if($coordinacionMatchs === false) { // Las coordinaciones no coinciden ?>
            <h3 class="text-danger h3">
                Su coordinación no corresponde
            </h3>
        <?php } else { ?>
            <?php
                // Validamos que no lo haya evaluado ya
                include_once '../models/evaluacion_model.php';
                $evaluacion_model = new EvaluacionModel();
                $canEvaluate = $evaluacion_model->UserCanEvaluate($_SESSION['neocaja_cedula'], $id_docente);
            ?>
            <?php if($canEvaluate === false) { ?>
                <h3 class="text-danger h3">
                    Usted no puede evaluar a <?= $docente['nombres'] . ' ' . $docente['apellidos'] ?> aún
                </h3>
            <?php } else { // Todo está bien, se procede a mostrar el formulario ?>
                <?php include_once 'evaluar_form.php'; ?>
            <?php } ?>
        <?php } ?>
    <?php } ?>
<?php } ?>
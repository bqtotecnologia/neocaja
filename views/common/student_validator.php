<?php if($_POST['docente'] === ''){ ?>
    <h2>Por favor rellene todos los campos</h2>
<?php } else { ?>
    <?php
    $id_docente = $_POST['docente'];
    $docente = $siacad->GetDocenteById($id_docente);
    ?>

    <?php if($docente === false) { ?>
        <h2>El docente que usted está buscando no existe</h2>
    <?php } else { ?>
        <?php 
        $docenteCorresponds = $siacad->DocenteTeachToStudent($id_docente, $_SESSION['neocaja_cedula']);
        if($docenteCorresponds === false) { // El docente no da clases al estudiante ?>
            <h2>El docente no la de clases a usted</h2>
        <?php } else { // El docente si le da clases al estudiante, pero debemos validar que no lo haya evaluado ya ?>
            <?php
                include_once '../models/evaluacion_model.php';
                $evaluacion_model = new EvaluacionModel();
                $canEvaluate = $evaluacion_model->UserCanEvaluate($_SESSION['neocaja_cedula'], $id_docente);
            ?>
            <?php if($canEvaluate === false) { ?>
                <h2>Usted ya ha evaluado a este docente en este corte</h2>
            <?php } else { // Todo está bien, se procede a mostrar el formulario ?>
                <?php include_once 'evaluar_form.php'; ?>
            <?php } ?>
        <?php } ?>
    <?php } ?>
<?php } ?>
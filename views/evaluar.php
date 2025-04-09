<?php
session_start();

// En esta sección se les muestra a los usuarios un listado de los docentes/coordinadores que pueden evaluar

// Si no existe la variable de sesión 'neocaja_user_id' devolvemos al usuario al index.php
if(!isset($_SESSION['neocaja_user_id'])){
    session_destroy();
    header('Location: /evadoc/index.php');
    exit;
}

include '../models/evaluacion_model.php';
include '../models/siacad_model.php';
$evaluacion_model = new EvaluacionModel();
$siacad = new SiacadModel();

if($_SESSION['neocaja_tipo'] === 'student')
    $my_docentes = $evaluacion_model->GetDocentesLeftOfEstudiante($_SESSION['neocaja_cedula']);
else if($_SESSION['neocaja_tipo'] === 'teacher')
    $my_docentes = $evaluacion_model->GetCoordinadoresLeftOfDocente($_SESSION['neocaja_cedula']);
else if($_SESSION['neocaja_tipo'] === 'coord')
    $my_docentes = $evaluacion_model->GetDocentesLeftOfCoordinador($_SESSION['neocaja_cedula']);

// Si es un docente/coordinador lo añadimos a él mismo a la lista si no se ha autoevaluado ya
if(in_array($_SESSION['neocaja_tipo'], ['teacher', 'coord'])){
    $autoevaluated = $evaluacion_model->GetAutoevaluacionOf($_SESSION['neocaja_user_id'], $_SESSION['neocaja_cedula']);
    if(!$autoevaluated){
        $current_docente = array(
            'iddocente' => $_SESSION['neocaja_user_id'],
            'nombres' => $_SESSION['neocaja_name'],
            'apellidos' => $_SESSION['neocaja_surname']
        );
        array_push($my_docentes, $current_docente);
    }
}

    if($my_docentes === false || $my_docentes === []){
    $redirect = '';
    if($_SESSION['neocaja_tipo'] === 'teacher')
        $redirect = 'Location: panel.php?message=1';
    else if($_SESSION['neocaja_tipo'] === 'coord')
        $redirect = 'Location: panel.php?message=0';
    else
        $redirect = 'Location: login.php?message=0';
    header($redirect);
    exit;
}

// Los posibles mensajes de error
$errors = array(
    'Ocurrió un error inesperado',
    'Alguna observación contiene caracteres sospechosos',
    'Por favor, rellene todos los campos',
    'Docente no encontrado',
    'La observación tiene demasiados caracteres'
);


// Posibles mensajes
$messages = array(
    'Evaluación creada exitosamente, por favor evalúe al siguiente docente',
    'Evaluación creada exitosamente, por favor evalúe al siguiente coordinador'
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Evaluar docente</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="../vendors/animate.css/animate.min.css" rel="stylesheet">
    <!-- select2 -->
    <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Title icon -->
    <link rel="apple-touch-icon" href="../images/iujo.ico">
    <link rel="shortcut icon" href="../images/iujo.ico">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
    <link href="../build/css/mycustom.css" rel="stylesheet">
  </head>

  <body class="evaluar_page p-3 w-100">
    <section class="row m-0 p-0 x_panel shadowed justify-content-center px-1">
        <div class="col-12 text-right pt-2">
            <button class="btn btn-info m-1">
                <?php if (in_array($_SESSION['neocaja_tipo'], ['coord', 'teacher'])) { ?>
                    <a class=" text-white text-center text-decoration-none p-0" href="panel.php">
                        Regresar
                <?php } else { ?>
                    <a class=" text-white text-center text-decoration-none p-0" href="../controllers/logout.php">
                        Salir
                <?php } ?>
                </a>
            </button>
        </div>

        <div class="col-12 row justify-content-center m-0 p-0">
            <h1 class="text-center col-12 mb-4 x_title" style="text-transform:uppercase">
                <?= "Bienvenido(a) " . $_SESSION['neocaja_name'] . ' ' . $_SESSION['neocaja_surname'] ?>
            </h1>
        </div>

        <?php if(isset($_GET['error'])) { ?>
            <h3 class="text-center col-12 mb-4 fw-bold h3 text-danger">
                <?= $errors[$_GET['error']] ?>
            </h3>
        <?php } ?>
        <?php if(isset($_GET['message'])) { ?>
            <h3 class="text-center col-12 mb-4 fw-bold h3 text-success">
                <?= $messages[$_GET['message']] ?>
            </h3>
        <?php } ?>

        <?php 
        // Colocamos la explicación correspondiente
        if($_SESSION['neocaja_tipo'] === 'student')
            include_once 'common/student_explanation.php';
        else
            include_once 'common/personal_explanation.php';            
        ?>

        <div class="row col-12 justify-content-center m-0 p-0 pt-3">
            <div class="row justify-content-center p-0 m-0">
                <div class="col-12">
                    <h2 class="text-center">
                        Por favor, escoja a su 
                        <?php 
                        if(in_array($_SESSION['neocaja_tipo'], ['student', 'coord'])) echo 'docente';
                        else echo 'coordinador';
                        ?>
                        y haga click en evaluar para comenzar la evaluación
                    </h2>
                </div>
                <form class="row justify-content-center m-0 p-0" method="POST">
                    <div class="col-12 justify-content-center m-0 p-0 select2-container">
                        <select class="select2 py-1 h6" id="select2" name="docente" required>
                            <option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                            <?php foreach($my_docentes as $docente) { ?>
                                <option class="h6" value="<?= $docente['iddocente'] ?>"
                                    <?php if(isset($_POST['docente'])) { if($_POST['docente'] == $docente['iddocente']) echo ' selected'; } ?>>
                                    <?= ucfirst($docente['nombres'] . ' ' . $docente['apellidos']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="m-0 my-2 col-12 d-flex justify-content-center align-items-center">
                        <button class="btn btn-success p-2 px-3" type="submit">Escoger</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <?php if(isset($_POST['docente'])) { ?>
        <section class="row m-0 p-0 my-3 justify-content-center">
            <div class="row col-12 justify-content-center px-0 px-md-5 pt-3">
                <div class="x_panel row col-12 justify-content-center p-0 m-0 shadowed">
                    <div class="row col-12 justify-content-center py-3 m-0">
                        <?php 
                        // Colocamos el formulario correspondiente
                            if($_SESSION['neocaja_tipo'] === 'student')
                            include_once './common/student_validator.php';
                        else
                            include_once 'common/personal_validator.php'; 
                        ?>
                    <div class="row col-12">
                        <h5 class="text-danger" id="error_message">

                        </h5>
                    </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="../build/js/validateEvaluacion.js"></script>
    <?php } ?>

    <style>
        .bottom-bordered{
            border-bottom: 3px black solid !important;
        }

        .empty-field{
            box-shadow: inset red 0 0 10px;
        }
    </style>
        
    <footer class="row m-0 p-0 justify-content-center mt-5" style="background:none;">
        <!-- jQuery -->
        <script src="../vendors/jquery/dist/jquery.min.js"></script>

        <!-- Bootstrap -->
        <script src="../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

        <!-- select2 -->
        <script src="../vendors/select2/dist/js/select2.min.js" rel="stylesheet"></script>

        <!-- Custom Theme Scripts -->
        <script src="../build/js/mycustom.js"></script>
        <div class="col-12 text-center">
            Copyright &copy; 2023 Sistema de evaluación de docentes
            Designed by <a href="https://www.iujobarquisimeto.edu.ve/">IUJO Barquisimeto</a>
        </div>
    </footer>
  </body>
</html>
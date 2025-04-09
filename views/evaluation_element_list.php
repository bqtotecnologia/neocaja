<?php 
session_start();
// Si el usuario NO es del tipo correcto, lo mandamos al index y cerramos la sesión
if(!in_array($_SESSION['neocaja_tipo'], ['admin', 'super'])){
    session_destroy();
    header('Location: ../index.php');
    exit;
}

if(!isset($_GET['element'])){
    header('Location: manage_criterios.php?error=0');
    exit;
}

if(!in_array($_GET['element'], ['categoria', 'dimension', 'indicador', 'enunciado', 'criterio', 'grupo_criterio'])){
    header('Location: manage_criterios.php?error=1');
    exit;
}

$element = $_GET['element'];
include_once '../models/criterio_model.php';
$criterio_model = new CriterioModel();

$evaluation_elements = $criterio_model->GetEvaluationElement($element);
$father_name = null;
$type_translator = [
    'coord' => 'coordinadores',
    'teacher' => 'docentes',
    'student' => 'estudiantes'
];

if($evaluation_elements !== false){
    $father_elements = [
        'dimension' => 'Categoria',
        'indicador' => 'Dimension',
        'enunciado' => 'Indicador',
        'criterio' => 'Grupo criterio'
    ];
    if(isset($evaluation_elements[0]['father_element'])){
        $father_name = $father_elements[$element];
    }
}

$errors = [
    'No se recibió ningún elemento',
    'Campos recibidos vacíos'
];


include 'common/header.php';
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">
            Gestión de 
            <?php 
            if($element === "grupo_criterio") 
                echo 'Grupo criterio';
            else 
                echo $element;
            ?>
        </h1>
    </div>
    
    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <div class="col-12 x_title">
                <a class="btn btn-app" href="manage_criterios.php">
                    <i class="fa fa-arrow-left" style="color:green"></i> Volver
                </a>
                <a href="add_<?= $element ?>.php" class="btn btn-app">
                    <i class="fa fa-plus" style="color:green"></i> Nuevo
                </a>
            </div>

            <?php if(isset($_GET['error'])) { ?>
                <div class="alert alert-danger alert-dismissible h3 col-6 m-auto text-center" role="alert">
                    <button type="button" class="close m-0" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <?= $errors[$_GET['error']] ?>
                </div>
            <?php } ?>

            <?php if(isset($_GET['message'])) { ?>
                <div class="alert alert-success alert-dismissible h3 col-6 m-auto text-center" role="alert">
                    <button type="button" class="close m-0" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <?= $_GET['message'] ?>
                </div>
            <?php } ?>
            
            <?php if($evaluation_elements === false) { ?>
                <div class="col-12 row justify-content-center">
                    <h3 class="text-center">No hay <?= $element ?>(s)</h3>
                </div>
            <?php } else { ?>
                <?php include 'common/evaluation_element_table.php'; ?>
            <?php } ?>
        </div>
    </div>
</div>

<?php include 'common/footer.php'; ?>
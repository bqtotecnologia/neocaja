<?php

/*
Aquí recibimos por POST una lista de los ids de los criterios los cuales
el usuario ha escogido, por lo tanto se procede a validar que todo
esté correcto y se crea la evaluación si todo está bien
*/

session_start();
if (!isset($_SESSION['eva_name'])) {
    header('Location: ../views/login.php?error=9');
    exit;
}

if(empty($_POST)){
    header('Location: ../views/evaluar.php?error=0');
    exit;
}

include_once '../models/siacad_model.php';
include '../models/evaluador_model.php';
$siacad = new SiacadModel();
$evaluador_model = new EvaluadorModel();
$id_docente = $_POST['docente'];

if($_SESSION['eva_tipo'] === 'student'){
    $already_evaluate = $evaluador_model->EvaluadorYaEvaluo($_SESSION['eva_cedula']);
    if($already_evaluate === true){
        // Ya evaluó del todo, no tiene nada más que evaluar
        session_destroy();
        header('Location: ../views/login.php?error=5');
        exit;
    }

    $docente = $siacad->DocenteTeachToStudent($id_docente, $_SESSION['eva_cedula']);
    
    if($docente === false){
        // El docente no da clases a ese estudiante
        header('Location: ../views/evaluar.php?error=1');
        exit;
    }
}
else{
    // Es un coordinador o docente
    $docente = $siacad->GetDocenteById($id_docente);
}

if($docente === false){
    // El docente no da clases a ese estudiante
    header('Location: ../views/evaluar.php?error=3');
    exit;
}

$enunciados = array();
$enunciados_ids = '';
$criterios_ids = '';
$i = 1;

foreach ($_POST as $key => $values) {
    if($key == 'observacion'){
        $observacion = $values;
    }else if ($key == 'docente'){
        $id_docente = $values;
    }else{
        // Es un criterio
        $enunciados[$key] = array();
        $enunciados_ids .= $key . ', ';
        if(is_array($values)){
            // Es una pregunta de selección múltiple
            $current_ids = '';
            foreach($values as $value){
                $current_ids .= $value . ', ';
                $criterios_ids .= $value . ', ';
            }
            $enunciados[$key] = trim($current_ids, ', ');
        }
        else{
            $enunciados[$key] = $values;
            $criterios_ids .= $values . ', ';
        }
    }
}

$enunciados_ids = trim($enunciados_ids, ', ');
$criterios_ids = trim($criterios_ids, ', ');



include '../models/criterio_model.php';
$criterio_model = new CriterioModel();
$real_criterios = $criterio_model->CheckCriteriosExists($enunciados);

if($real_criterios === false){
    // Si los criterios que llegan no coinciden con los reales hay algo raro
    header('Location: ../views/evaluar.php?error=2');
    exit;
}

// < > / \\ ; " ( ) { } [ ] $ & | ¿ ? ¡ ! = -   
$regex = '/[<>\-\/;"\'(){}\[\]$\\\|&\?\¿\¡!=]/u';
if (preg_match($regex, $observacion)){
    // El texto puede ser malicioso
    header('Location: ../views/evaluar.php?error=1');
    exit;
}

if(strlen($observacion) > 300){
    header('Location: ../views/evaluar.php?error=4');
    exit;
}

// Validamos que el usuario no haya evaluado ya en este corte
$evaluador = $evaluador_model->EvaluadorExists($_SESSION['eva_cedula']);

if($evaluador === false){
    // El usuario no ha sido registrado, entonces lo creamos
    $evaluador = $evaluador_model->CreateEvaluador($_SESSION['eva_cedula']);
}

$corte_to_vote = $siacad->GetCorteToVote();
if($corte_to_vote == 'I') $corte_largo = 'corte_1';
else if($corte_to_vote == 'II') $corte_largo = 'corte_2';
else if ($corte_to_vote == 'III') $corte_largo = 'corte_3';
else{
    // El usuario trata de evaluar en una fecha rara
    session_destroy();
    header('Location: ../views/login.php?error=6');
    exit;
}

/*
Si el código llega hasta acá, todo está absolutamente bien, así que se procede a crear
la evaluación
*/
include '../models/evaluacion_model.php';
include '../models/docentes_periodos_model.php';
$evaluacion_model = new EvaluacionModel();
$docentes_periodos = new DocentesPeriodosModel();
$data = [
    'cedula' => $_SESSION['eva_cedula'],
    'docente' => $docente['iddocente'],
    'observacion' => $observacion,
    'criterios' => $real_criterios,
    'enunciados' => $enunciados,
    'enunciados_ids' => $enunciados_ids,
    'user_type' => $_SESSION['eva_tipo']
];

$result = $evaluacion_model->CreateEvaluacion($data);

if($result === false){
    // La evaluación ya existe
    header('Location: ../views/evaluar.php?error=0');
    exit;
}

$redirect = 'Location: ../views/evaluar.php?message=0';
if($_SESSION['eva_tipo'] === 'student'){
    // Una vez creada la evaluación, verificamos si le quedan docentes por evaluar, 
    // Si no tiene ya terminó de evaluar en el corte
    $remainings_teachers = $docentes_periodos->EstudianteHaveRemainingDocentes($_SESSION['eva_cedula']);
    if($remainings_teachers === false){
        // el estudiante ya evaluó a todos los docentes
        $evaluador_model->UpdateEvaluatedCorte($_SESSION['eva_cedula'], $corte_largo);
        session_destroy();
        $redirect = 'Location: ../views/login.php?message=0';
    }
}
else if ($_SESSION['eva_tipo'] === 'teacher')
    $redirect = 'Location: ../views/evaluar.php?message=1';

header($redirect);
exit;
?>
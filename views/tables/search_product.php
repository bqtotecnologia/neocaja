<?php
    include_once 'common/header.php';

    if(!in_array($_SESSION['neocaja_tipo'], ['super', 'teacher', 'coord', 'admin'])){
        session_destroy();
        header('Location:../index.php');
        exit;
    }

    include_once '../models/docentes_periodos_model.php';
    $docentes_periodos = new DocentesPeriodosModel();
    if($_SESSION['neocaja_tipo'] === 'coord')
        $docentes = $docentes_periodos->GetAllEvaluatedDocentesOfCoordinador($_SESSION['neocaja_user_id']);
    else
        $docentes = $docentes_periodos->GetAllEvaluatedDocentes();
    $self_docente = false;
    if(isset($_POST['docente'])){
        $self_docente = intval($_POST['docente']) === $_SESSION['neocaja_user_id'];
    }
?>

<div class="row justify-content-around text-center">
    <?php if($self_docente === false) { ?>
        <div class="col-12 row m-0 p-2">
            <h1 class="col-12 m-0 p-0 h1">
                Buscar a un docente para ver sus evaluaciones
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
    
        <div class="col-12 row m-0 p-2 justify-content-center">
            <?php if($docentes === [] || $docentes === false) { ?>
                <h3 class="col-6 text-danger">No ha sido evaluado ningún docente</h3>
            <?php } else { ?>
                <form class="row justify-content-center m-0 p-0 col-8 col-md-3" method="POST">
                    <div class="col-12 justify-content-center m-0 p-0 select2-container">
                        <select class="select2 py-1 h6" id="select2" name="docente" required>
                            <option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                            <?php foreach($docentes as $docente) { ?>
                                <option class="h6" value="<?= $docente['iddocente'] ?>"
                                    <?php if(isset($_POST['docente'])) { if($_POST['docente'] == $docente['iddocente']) echo ' selected'; } ?>>
                                    <?= ucfirst($docente['nombres'] . ' ' . $docente['apellidos']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="m-0 my-2 col-12 d-flex justify-content-center align-items-center">
                        <button class="btn btn-success p-2 px-3" type="submit">Ver estadísticas</button>
                    </div>
                </form>
            <?php } ?>
        </div>
    <?php } ?>

    <?php if(isset($_POST['docente'])) { ?>
        <div class="col-12 row m-0 p-2 justify-content-around align-items-start">
            <?php if($_POST['docente'] === '') { ?>
                <h3 class="col-6 text-danger">Escoja a un docente por favor</h3>
            <?php } else { ?>
                <?php
                    include_once '../models/siacad_model.php';
                    $siacad = new SiacadModel();
                    $target_docente = $siacad->GetDocenteById($_POST['docente']);
                ?>
                <div class="row col-12 col-md-6 p-2 justify-content-center">
                    <div class="x_panel d-flex row col-12 py-3 px-0 justify-content-center align-items-center">
                        <div class="col-10 d-flex justify-content-center">
                            <figure class="w-50 text-center">
                                <?php 
                                $image_url = '../../sisnom//images/carnets/' . $target_docente['cedula'] . '.jpg';
                                if(!file_exists($image_url))
                                    $image_url = '../images/personal/interrogante.png';
                                ?>
                                <img class="w-100 text-center" src="<?= $image_url ?>" alt="docente image">
                            </figure>
                        </div>
                        <table class="table-secondary col-8">
                            <tr>
                                <td class="h5 fw-bold">
                                    Nombre completo
                                </td>
                                <td class="h5">
                                    <?= $target_docente['nombres'] . ' ' . $target_docente['apellidos'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="h5 fw-bold">
                                    Cédula
                                </td>
                                <td class="h5">
                                    <?= $target_docente['cedula'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="h5 fw-bold">
                                    Teléfono
                                    </td>
                                <td class="h5">
                                    <?= $target_docente['telefono'] === null || $target_docente['telefono'] === '' ? 'No establecido' : $target_docente['telefono'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="h5 fw-bold">
                                    Correo
                                </td>
                                <td class="h5">
                                    <?= $target_docente['email'] === null || $target_docente['email'] === '' ? 'No establecido' : $target_docente['email'] ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php
                    $cortes_largos = [
                        'I' => 'Corte 1',
                        'II' => 'Corte 2',
                        'III' => 'Corte 3'
                    ];
                    include_once '../models/evaluacion_model.php';
                    $evaluacion_model = new EvaluacionModel();
                    $ordered_periodos = $evaluacion_model->DocenteHaveEvaluaciones($target_docente['iddocente']);
                    $specific_periodo = false;
                ?>
                <div class="row col-10 p-2 justify-content-center">
                    <div class="x_panel d-flex row col-12 p-2 justify-content-center">
                        <div class="col-12">
                            <h3>Periodos en los que ha sido evaluado</h3>
                        </div>
                        <div class="col-4 justify-content-center m-0 p-0 select2-container">
                            <select class="select2 py-1 h6 periodo-selector" id="select2 periodo-selector" name="periodo-selector" onchange="ChangePeriodo(this.value);" onfocus="this.selectedIndex = -1;">
                                <option value=""></option>
                                <?php foreach($ordered_periodos as $periodo => $periodo_data) { ?>
                                    <option class="h6" value="<?= $periodo ?>"
                                    <?php 
                                        if(isset($_POST['periodo'])) {
                                            if(intval($_POST['periodo']) === $periodo){
                                                echo ' selected';
                                                $specific_periodo = $periodo;
                                            }
                                        } 
                                    ?>
                                    >
                                    
                                    <?= $siacad->GetPeriodoById($periodo)['nombreperiodo'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="m-0 my-2 col-12 d-flex justify-content-center align-items-center">
                        </div>

                        <div class="row col-12 col-md-7 p-2 justify-content-center">
                            <table class="table-secondary col-12 col-md-10 d-none" id="periodos-table">
                                <thead>
                                    <tr>
                                        <th colspan="20" class="h4 fw-bold">
                                            Estadísticas
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="h5 fw-bold px-5">
                                            Corte
                                        </th>
                                        <th class="h5 fw-bold px-2">
                                            Estudiantes
                                        </th>
                                        <th class="h5 fw-bold px-2">
                                            Coordinadores
                                        </th>
                                        <th class="h5 fw-bold px-2">
                                            Docentes
                                        </th>
                                        <th class="h5 fw-bold px-2">
                                            Observaciones
                                        </th>
                                    </tr>
                                </thead>
                                <?php
                                    $sure_evaluations = array(
                                        'student' => false,
                                        'coord' => false,
                                        'teacher' => false,
                                        'observaciones' => false
                                    );
                                ?>
                                <?php foreach($ordered_periodos as $periodo => $cortes) { ?>
                                    <tbody id="periodo-<?= $periodo ?>" class="d-none periodo-content">
                                        <?php foreach($cortes as $corte => $tipo_evaluaciones) { ?>
                                            <tr>
                                            <td class="h4 fw-bold">
                                                <?= $cortes_largos[$corte] ?>
                                            </td>
                                            <?php foreach($tipo_evaluaciones as $tipo_evaluacion => $valor) { ?>
                                                <td>
                                                    <?php if($valor === true) { ?>
                                                        <?php $sure_evaluations[$tipo_evaluacion] = true; ?>
                                                        <?php 
                                                            if($tipo_evaluacion === 'observaciones')
                                                                $base_url = 'see_observaciones';
                                                            else
                                                                $base_url = 'see_evaluation';

                                                            $post_url = '.php?docente=' . $target_docente['iddocente'] .
                                                                "&periodo=$periodo&corte=$corte&tipo=$tipo_evaluacion";
                                                        ?>
                                                        <?php if($tipo_evaluacion !== 'observaciones') { ?>
                                                            <a href="<?= $base_url . $post_url . '&show_as=graphs' ?>">
                                                                <button class="btn btn-info m-1">
                                                                    <i class="fa fa-pie-chart"></i>
                                                                </button>
                                                            </a>
                                                            <?php $base_url = 'see_evaluation'; ?>
                                                        <?php } ?>
                                                        <a href="<?= $base_url . $post_url . '&show_as=report' ?>">
                                                            <button class="btn btn-primary m-1">
                                                            <i class="fa fa-file-text-o"></i>
                                                            </button>
                                                        </a>
                                                        <?php if($tipo_evaluacion === 'student' && !$self_docente) { ?>
                                                            <a href="evaluaciones_by_seccion.php?docente=<?= $target_docente['iddocente'] ?>&corte=<?= $corte ?>&periodo=<?= $periodo ?>">
                                                                <button class="btn btn-primary m-1">
                                                                    Por sección
                                                                </button>
                                                            </a>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <h5 class="text-danger m-0">No tiene</h5>
                                                    <?php } ?>
                                                </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td class="h4 fw-bold">
                                                Resumen
                                            </td>
                                            <?php foreach($sure_evaluations as $tipo_evaluacion => $value) { ?>
                                                <td>
                                                    <?php if($value === true) { ?>
                                                        <?php 
                                                            if($tipo_evaluacion === 'observaciones')
                                                                $base_url = 'see_observaciones';
                                                            else
                                                                $base_url = 'see_evaluation';

                                                            $post_url = '.php?docente=' . $target_docente['iddocente'] .
                                                                "&periodo=$periodo&tipo=$tipo_evaluacion";
                                                        ?>
                                                        <?php if($tipo_evaluacion !== 'observaciones') { ?>
                                                            <a href="<?= $base_url . $post_url . '&show_as=graphs' ?>">
                                                                <button class="btn btn-info m-1">
                                                                    <i class="fa fa-pie-chart"></i>
                                                                </button>
                                                            </a>
                                                            <?php $base_url = 'see_evaluation'; ?>
                                                        <?php } ?>
                                                        <a href="<?= $base_url . $post_url . '&show_as=report' ?>">
                                                            <button class="btn btn-primary m-1">
                                                                <i class="fa fa-file-text-o"></i>
                                                            </button>
                                                        </a>
                                                        <?php if($tipo_evaluacion === 'student') { ?>
                                                            <a href="evaluaciones_by_seccion.php?docente=<?= $target_docente['iddocente'] ?>&periodo=<?= $periodo ?>">
                                                                <button class="btn btn-primary m-1">
                                                                    Por sección
                                                                </button>
                                                            </a>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <h5 class="text-danger m-0">No tiene</h5>
                                                    <?php } ?>   
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    </tbody>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<script>
    const periodoContents = document.getElementsByClassName('periodo-content')
    function ChangePeriodo(idPeriodo){   
        // Mostamos la tabla que por defecto está oculta
        document.getElementById('periodos-table').classList.remove('d-none')
        // Ocultamos todos los periodos anteriores   
        for (let element of periodoContents){
            element.classList.add('d-none')
        }
        if(idPeriodo === '') return
        // Mostramos el que vamos a mostrar
        var to_show = document.getElementById('periodo-' + idPeriodo)
        to_show.classList.remove('d-none')
    }

</script>

<?php include 'common/footer.php'; ?>

<?php if($specific_periodo !== false) { ?>
    <script>
        ChangePeriodo("<?= $specific_periodo ?>")
    </script>
<?php } ?>
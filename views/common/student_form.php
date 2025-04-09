<?php 
include_once '../models/criterio_model.php';
$criterio_model = new CriterioModel();

$materias = $siacad->GetMateriasOfDocenteAndEstudiante($_SESSION['eva_cedula'], $docente['cedula']);
$categorias = $criterio_model->GetCategoriasOf($_SESSION['eva_tipo']);

include_once 'docente_displayer.php';
?>

<form class="row m-0 p-0 justify-content-center" action="../controllers/create_evaluacion.php" method="POST" id="EvaluacionForm">
    <input type="hidden" name="docente" value="<?= $docente['iddocente'] ?>">
    <?php foreach ($categorias as $categoria_name => $categoria_content) { ?>
        <h3 class="w-100 text-center mt-5 x_title"><?= $categoria_name ?></h3>
        <?php foreach ($categoria_content['dimensiones'] as $dimension_name => $dimension_content) { ?>
            <table class="col-12 col-md-11 m-0 p-0 my-3 text-break table-secondary table-responsive-md text-black">
                <thead>
                    <tr>
                        <th colspan="6" class="border border-black text-center h4">
                            <?= $dimension_name ?>
                        </th>
                    </tr>
                    <tr>
                        <th class="border border-black text-center fw-bold">Indicador</th>
                        <th class="border border-black text-center fw-bold">Enunciado</th>
                        <th class="border border-black text-center fw-bold px-5">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dimension_content['indicadores'] as $indicador_name => $indicador_content) { ?>
                        <tr>
                            <td rowspan="<?= $indicador_content['height'] ?>" class="border border-black text-center h5 bottom-bordered">
                                <?= $indicador_name ?>
                            </td>
                            <?php foreach ($indicador_content['enunciados'] as $enunciado_name => $enunciado_content) { ?>
                                <?php $td_id = 'enu-' . $enunciado_content['id']; ?>
                                <td rowspan="<?= $enunciado_content['height'] ?>" class="border border-black px-1 bottom-bordered " id="<?= $td_id ?>[]">
                                    <?= $enunciado_name ?>
                                </td>
                                <?php $i = 0; foreach ($enunciado_content['criterios'] as $criterio_name => $criterio_content) { ?>
                                    <?php $input_name = $enunciado_content['id'] . '-' . $criterio_content['id'] ?>
                                    <td class="text-left p-1 border-black w-auto
                                    <?php if ($i == count($enunciado_content['criterios']) - 1) {
                                        echo ' bottom-bordered ';
                                    } ?>"
                                    >
                                        <input 
                                            type="<?php if ($enunciado_content['checkbox'] == 't') { echo 'checkbox'; } else { echo 'radio'; } ?>"
                                            name="<?=  $enunciado_content['id'] ?>[]" 
                                            value="<?= $criterio_content['id'] ?>" 
                                            id="<?= $input_name ?>"
                                            
                                            <?php
                                                if($enunciado_content['checkbox'] == 't'){
                                                    if(ucfirst($criterio_name) === 'Ninguno'){
                                                        echo 'onclick="UnmarkOptions(' . $enunciado_content['id'] . ')"';
                                                        echo 'class="my-1 align-middle ninguno"';
                                                    }
                                                    else{
                                                        echo 'onclick="UnmarkNone(' . $enunciado_content['id'] . ')"';
                                                        echo 'class="my-1 align-middle"';
                                                    }
                                                }
                                                else
                                                    echo 'class="my-1 align-middle"';
                                            ?>
                                        >
                                        <label class="align-middle m-0 mx-1" for="<?= $input_name ?>">
                                            <?= $criterio_name ?>
                                        </label>
                                    </td>
                                </tr>
                                <?php $i++; } // foreach $criterio ?>
                            <?php } // foreach $enunciado ?>
                        </tr>
                    <?php } // foreach $indicador ?>
                </tbody>
            </table>
        <?php } // foreach $dimension ?>
    <?php } // foreach $categoria ?>

    <div class="col-12 mt-2 px-4 mt-3">
        <label class="h5" for="observación">
            Indique algún comentario, observación o recomendación (Opcional)
        </label>
        <textarea 
        class="w-100" 
        name="observacion" 
        id="observacion" 
        maxlength="300"
        cols="20"
        rows="4" 
        placeholder="Evitar los siguientes caracteres: < > / \\ ; ( ) { } [ ] $ & | ¿ ? ¡ ! - = ' + &quot;"
        ></textarea>
    </div>
    <div class="col-12 p-2 d-flex justify-content-center">
        <button class="btn btn-danger" type="submit">Evaluar docente</button>
    </div>
    <div class="col-12">
        <h2 class="text-center mb-5 text-danger" id="error-displayer">
            <!-- Espacio para colocar errores que detecte el javascript -->
        </h2>
    </div>
    
</form>

<script>
    const UnmarkOptions = (inputName) => {
        var inputs = document.getElementsByName(inputName + '[]');
        inputs.forEach((input) => {
            console.log(input.classList.contains('ninguno'));
            if(!input.classList.contains('ninguno'))
                input.checked = false;
        })
    };

    const UnmarkNone = (inputName) => {
        var inputs = document.getElementsByName(inputName + '[]');
        inputs.forEach((input) => {
            if(input.classList.contains('ninguno'))
                input.checked = false;
        })
    }
</script>
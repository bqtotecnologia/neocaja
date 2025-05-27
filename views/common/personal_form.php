<?php 
include_once '../models/criterio_model.php';
$criterio_model = new CriterioModel();

// Si el docente/coordinador se está autoevaluando, buscamos los criterios de evaluación a ellos mismos
if($docente['iddocente'] === $_SESSION['neocaja_user_id']){
    if($_SESSION['neocaja_rol'] === 'teacher')
        $categorias = $criterio_model->GetCategoriasOf('coord');
    else
        $categorias = $criterio_model->GetCategoriasOf('teacher');
}else
    $categorias = $criterio_model->GetCategoriasOf($_SESSION['neocaja_rol']);

$materias = $siacad->GetMateriasByCedulaDocente($docente['cedula']);
?>

<form class="row m-0 p-0 justify-content-center" action="../controllers/create_evaluacion.php" method="POST" id="EvaluacionForm">
    <input type="hidden" name="docente" value="<?= $docente['iddocente'] ?>">
    <div class="row justify-content-around align-items-start">
        <?php include_once 'docente_displayer.php'; ?>
            <?php foreach ($categorias as $categoria => $enunciados) { ?>
                <table class="col-12 col-md-11 m-0 p-0 my-3 text-break table-secondary table-responsive-md text-black">
                    <thead>
                        <tr>
                            <th colspan="6" class="border border-black align-center text-center fw-bold py-2 h5">
                                <span class="h2">
                                    <?= $categoria ?>
                                </span>
                            </th>
                        </tr>
                        <tr>
                            <th class="border border-black text-center fw-bold h4">
                                Enunciado
                            </th>
                            <th class="border border-black text-center fw-bold px-5 h4">
                                Opciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($enunciados as $enunciado => $enunciado_content) { if($enunciado === 'id') continue; ?>
                            <tr>
                                <?php
                                    $criterios = $enunciado_content['criterios'];
                                    $td_id = 'enu-' . $enunciado_content['id'];
                                 ?>
                                <td class="border border-black px-1" id="<?= $td_id ?>">
                                    <?= $enunciado ?>
                                </td>
                                <td class="border border-black p-1 text-center">
                                    <table class="w-100">
                                        <?php foreach($criterios as $criterio_name => $criterio) { ?>
                                            <tr>
                                                <td>
                                                    <?php $input_name = $criterio['id_indicador'] . '-' . $criterio['id_enunciado'] . '-' . $criterio['id_criterio'] ?>
                                                    <input 
                                                        type="radio"
                                                        name="<?= $criterio['id_enunciado'] ?>"
                                                        value="<?= $criterio['id_criterio'] ?>" 
                                                        id="<?= $input_name ?>"
                                                        class="my-1 align-middle"
                                                    >
                                                </td>
                                                <td class="text-left">
                                                    <label class="text-left align-middle my-0 mx-1" for="<?= $input_name ?>">
                                                        <?= $criterio_name ?>
                                                    </label>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
            <div class="col-12 mt-2 px-4 mt-3">
                <label class="h5" for="observacion">
                    Observación adicional (Opcional)
                </label>
                <br>
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

        </div>
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
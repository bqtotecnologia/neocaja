<?php 
include_once '../models/evaluacion_model.php';
$evaluacion_model = new EvaluacionModel();
$final_total = 0; 
$max = 0;
?>

<?php foreach($categorias as $categoria => $dimensiones) { ?>
    <h2 class="w-100 text-center h2" style="text-decoration:underline"><?= $categoria ?></h2>
    <div class="col-12 rounded">
        <?php foreach($dimensiones as $dimension => $indicadores) { ?>
            <h2 class="w-100 h2 text-center"><?= $dimension ?></h2>
            <div class="col-12 row x_title">
                <?php foreach($indicadores as $indicador => $enunciados) { ?>
                    <div class="col-12 col-lg-6 row p-3 align-items-start justify-content-center">
                        <div class="col-12 m-0 p-0 bg-white p-2 shadowed">
                            <table class="col-12">
                                <thead>
                                    <tr>
                                        <th colspan="8">
                                            <h3 class="col-12 h3 text-center"><?= $indicador ?></h3>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            Enunciado
                                        </th>
                                        <th>
                                            Criterio
                                        </th>
                                        <th>
                                            Cantidad
                                        </th>
                                        <th>
                                            Sumatoria
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>                            
                                    <?php 
                                    $indicador_max_posible = 0; 
                                    $indicador_total = 0; 
                                    foreach($enunciados as $enunciado => $enunciado_content) { ?>
                                        <?php 
                                            $enunciado_total = 0; 
                                            $criterios = $enunciado_content['criterios'];
                                            $indicador_max_posible += $enunciado_content['maximo_posible'];
                                        ?>
                                        <tr>
                                            <td class="enunciado-cell" rowspan="<?= count($criterios) ?>">
                                                <?= $enunciado ?>
                                            </td>
                                            <?php $first = true; ?>
                                            <?php foreach($criterios as $criterio_name => $criterio) { ?>
                                                <?php if(!$first) echo '<tr>'; ?>
                                                <?php 
                                                $peso_total = $criterio['peso'] * $criterio['cantidad'];
                                                $final_total += $peso_total; 
                                                $indicador_total += $peso_total;
                                                ?>
                                                <td class="text-center">
                                                    <?= $criterio_name ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= $criterio['cantidad'] ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= $peso_total ?>
                                                </td>
                                                <?php  echo '</tr>'; $first = false; ?>
                                            <?php } // foreach criterio ?>
                                                
                                    <?php } // foreach enunciado 
                                        $max += $indicador_max_posible; 
                                    ?>
                                    <tr>
                                        <?php if($indicador_max_posible !== 0) { ?> 
                                            <td class="empty-td"></td>
                                            <td class="empty-td"></td>
                                            <td class="text-center fw-bold">Nota</td>
                                            <td class="text-center fw-bold">
                                                <?= round(($indicador_total * 20) / ($indicador_max_posible * $evaluation_number), 1) ?> /20
                                            </td>
                                        <?php } ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } // foreach indicador ?>
            </div>
        <?php } // foreach dimension ?>
    </div>
<?php } // foreach categoria ?>
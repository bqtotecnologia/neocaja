<?php 
    include_once '../models/evaluacion_model.php';
    $evaluacion_model = new EvaluacionModel();
    $final_total = 0; 
    ?>

    <?php foreach($categorias as $categoria => $dimensiones) { ?>
        <h1 class="w-100 text-center h1"><?= $categoria ?></h1>
        <div class="col-12 x_panel rounded">
            <?php foreach($dimensiones as $dimension => $indicadores) { ?>
                <h2 class="w-100 h2 text-center"><?= $dimension ?></h2>
                <div class="col-12 row x_title">
                    <?php foreach($indicadores as $indicador => $enunciados) { ?>
                        <div class="col-12 col-lg-6 row justify-content-center">
                            <h3 class="col-12 h3 text-center"><?= $indicador ?></h3>
                            <?php foreach($enunciados as $enunciado => $enunciado_content) { ?>
                                <?php $criterios = $enunciado_content['criterios']; ?>
                                <div class="col-12 col-md-6">
                                    <div id="<?= $enunciado ?>">
                                    </div>
                                    <script>
                                        var data = [];
                                    </script>
                                    <?php foreach($criterios as $criterio_name => $criterio) { ?>
                                        <?php $final_total += $criterio['peso'] * $criterio['cantidad'] ?>
                                        <script>
                                            data.push(["<?= $criterio_name ?>", parseInt("<?= $criterio['cantidad'] ?>")]);
                                        </script>
                                    <?php } // foreach criterio ?>
                                    <script>
                                        // Llenamos de información el gráfico
                                        Highcharts.chart('<?= $enunciado ?>', {
                                            chart: {
                                                type: 'pie'
                                            },
                                            plotOptions: {
                                                pie: {
                                                    dataLabels: {
                                                        enabled: true,
                                                        format: '<b>{point.name}</b>: {point.percentage:.1f}%'
                                                    }
                                                }
                                            },
                                            title: {
                                                text: '<?= $enunciado ?>'
                                            },
                                            series: [{
                                                name: 'Cantidad',
                                                data: data
                                            }]
                                        });
                                    </script>
                                </div>
                            <?php } // foreach enunciado ?>
                        </div>
                    <?php } // foreach indicador ?>
                </div>
            <?php } // foreach dimension ?>
        </div>
    <?php } // foreach categoria ?>
<?php 
    include_once '../models/evaluacion_model.php';
    $evaluacion_model = new EvaluacionModel();
    $final_total = 0; 
    $indicadores = $categorias;
?>

<?php foreach($indicadores as $indicador => $enunciados) { ?>
    <div class="col-12 row justify-content-center x_panel">
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
                    <?php $final_total += $criterio['peso'] * $criterio['cantidad']; ?>
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
<?php
include_once '../models/evaluacion_model.php';
$evaluacion_model = new EvaluacionModel();
$totals = ['I' => 0, 'II' => 0, 'III' => 0];

$subjects_by_corte = $evaluacion_model->GetEvaluationsNumbersByType($target_periodo);
$evaluations_by_carreer = $evaluacion_model->GetEvaluationsByCarreer($target_periodo); 
$total_evaluations = 0;
?>
<div class="row col-12 row m-0 p-0 justify-content-around align-items-start">
    <?php if(in_array($subjects_by_corte, [false, []])) { ?>
        <h2 class="h2 w-100 text-center">No hay evaluaciones en este periodo</h2>
    <?php } else { ?>
        <div class="row col-12 col-md-4 m-0 p-0 justify-content-center">
            <table class="col-12 table-secondary h6">
                <thead class="text-center h4">
                    <tr>
                        <th rowspan="2">Sujeto</th>
                        <th colspan="3">Cantidad</th>
                    </tr>
                    <tr>
                        <th class="corte-header">I</th>
                        <th class="corte-header">II</th>
                        <th class="corte-header">III</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($subjects_by_corte as $subject => $content) { ?>
                        <tr class="text-center">
                            <td class="fw-bold h6"> <?= $subject ?> </td>
                            <td>
                                <?php 
                                echo $content['I']['cantidad'] . '<span>/</span>' . $content['I']['maximo'];
                                $totals['I'] += $content['I']['cantidad'];
                                $total_evaluations += $content['I']['cantidad'];
                                ?>
                            </td>
                            <td>
                                <?php 
                                echo $content['II']['cantidad'] . '<span>/</span>' . $content['II']['maximo'];
                                $totals['II'] += $content['II']['cantidad'];
                                $total_evaluations += $content['II']['cantidad'];
                                ?>
                            </td>
                            <td>
                                <?php 
                                echo $content['III']['cantidad'] . '<span>/</span>' . $content['III']['maximo'];
                                $totals['III'] += $content['III']['cantidad'];
                                $total_evaluations += $content['III']['cantidad'];
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr class="text-center">
                        <td class="fw-bold">Total</td>
                        <td><?= $totals['I'] ?></td>
                        <td><?= $totals['II'] ?></td>
                        <td><?= $totals['III'] ?></td>
                    </tr>
                </tbody>
            </table>
    
            <div class="row col-12 m-0 p-0 justify-content-center">
                <h3 class="h3">Evaluaciones totales registradas: 
                    <strong><?= $total_evaluations ?></strong>
                </h3>
            </div>
        </div>
    
    
        <script>
            var careers = [];
            var totals = [];
            var colors = {
                'Educación': '#2AA752',
                'Administración': '#80142D',
                'Contaduría': '#80142D',
                'Electrónica': '#46464A',
                'Electrotecnia': '#46464A',
                'Informática': '#1E4C92',
                'Mecánica': '#FDC032',
            };
            var real_colors = [];
        </script>
        <!-- Highcharts -->
        <script src="../vendors/highcharts/highcharts.js"></script>
        
        <div class="row col-12 col-md-7 m-0 p-0 justify-content-center">
        <div class="col-12">
                <div class="x_panel">
                <div class="x_title">
                    <h2> Estudiantes por carreras </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
    
                    <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="corte-I-tab" data-toggle="tab" href="#corte-I" role="tab" aria-controls="corte-I" aria-selected="true">Corte I</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="corte-II-tab" data-toggle="tab" href="#corte-II" role="tab" aria-controls="corte-II" aria-selected="false">Corte II</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="corte-III-tab" data-toggle="tab" href="#corte-III" role="tab" aria-controls="corte-III" aria-selected="false">Corte III</a>
                    </li>
                    </ul>
                    
                    <div class="tab-content" id="myTabContent">
                        <?php $first = true; ?>
                        <?php foreach($evaluations_by_carreer as $corte => $carreers) { ?>
                            <div 
                            class="tab-pane fade <?php if ($first) { echo 'show active'; $first = false;} ?>" 
                            id="corte-<?= $corte ?>" 
                            role="tabpanel" 
                            aria-labelledby="corte-<?= $corte ?>-tab"
                            >
                            <?php if($carreers === []) { ?>
                                <div class="w-100">
                                    <h3 class="text-center w-100">Sin evaluaciones</h3>
                                </div>
                            <?php } else { ?>
                                <div class="w-100" id="<?= $corte ?>-graph">
                                </div>
                                
                                <?php foreach ($carreers as $carreer => $total) { ?>
                                    <script>
                                        careers.push('<?= $carreer ?>');
                                        totals.push(parseInt('<?= $total ?>'));
                                        real_colors.push(colors['<?= $carreer ?>']);
                                    </script>
                                <?php } ?>
    
                                <script>
                                    new Highcharts.Chart({
                                        chart:{
                                            type: 'column',
                                            renderTo: '<?= $corte ?>-graph'
                                        },
                                        plotOptions: {
                                            column: {
                                                colorByPoint: true
                                            }
                                        },
                                        colors: real_colors,
                                        title: {
                                            text: 'Estudiantes que han evaluado'
                                        },
                                        xAxis: {
                                            title: {
                                                text: 'Carreras'
                                            },
                                            categories: careers,
                                            pointWidth: 1,
                                        },
                                        yAxis: {
                                            title: {
                                                text: 'Evaluaciones'
                                            },
                                            pointWidth: 1,
                                        },
                                        series: [{
                                            name: 'Cantidad',
                                            data: totals,
                                            pointWidth: 60
                                        }],
                                    });
                                    
                                    
                                    careers = []
                                    totals = []
                                    real_colors = [];
                                </script>
                            <?php } ?>
                            </div>
                        <?php } ?>
                    
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
    <?php } ?>
</div>

<style>
    .corte-header{
        width:80px;
    }
</style>
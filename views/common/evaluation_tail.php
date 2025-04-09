<div class="col-12 row justify-content-center py-3">
    <div class="col-8 col-md-6 x_panel rounded shadowed">
        <div class="row w-100 m-0 p-0 justify-content-center p-2">
            <?php $final_total = ($final_total * 20) / ($max * $evaluation_number); ?>
            <table class="col-6 table-info text-center">
                <tr>
                    <td class="h3 fw-bold">
                        Evaluaciones
                    </td>
                    <td class="h3 px-3 fw-bold">
                        <?= $evaluation_number ?>
                    </td>
                </tr>
                <tr>
                    <td class="h3 fw-bold">
                        Nota final
                    </td>
                    <td class="h3 px-3 fw-bold">
    
                        <?= round($final_total, 1) . '/20' ?>
                    </td>
                </tr>
            </table>
        </div>
        <h2 class="col-12 text-center h2 m-0">
            Nivel de desempeño: 
            <strong>
            <?php 
                if($final_total >= 18) echo 'Excelente';
                else if($final_total >= 16) echo 'Sobresaliente';
                else if($final_total >= 14) echo 'Distinguido';
                else if($final_total >= 12) echo 'Satisfactorio';
                else echo 'Deficiente';
            ?>
            </strong>
        </h2>
    </div>
</div>

<div class="col-12 row text-center p-3 pt-5 justify-content-around print-hidden">
    <div class="col-4 pt-5">
        <h4 class="h4" style="border-top: 2px black solid;">
            Firma del Coordinador
        </h4>
    </div>
    <div class="col-4 pt-5">
        <h4 class="h4" style="border-top: 2px black solid;">
            Firma del Docente
        </h4>
    </div>
</div>
<div class="col-12 text-center p-3 no-print">
    <button class="w-25 btn btn-info" onclick="ExportToPDF()">
        Guardar como PDF
    </button>
</div>

<style>
    th,td{
        background:#E1E1E1;
        border:1px solid black;
        text-align: center;
        padding:5px;
    }

    .enunciado-cell{
        width:40%;
        padding:5px;
    }

    .empty-td{
        background:none !important;
        opacity:0;
        border:none !important;
    }
</style>
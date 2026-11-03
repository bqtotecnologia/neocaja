<table class="table table-striped col-12 generic-datatable">
    <thead class="bg-theme text-white fw-bold h6">
        <tr>
            <th class="text-center" style="padding-right:15px !important;">Nº</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Desde</th>
            <th class="text-center">Hasta</th>
            <th class="text-center">Acción</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($period_ranges as $period_range) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $periods[$period_range['period']]['nombreperiodo'] ?></td>
                <td class="align-middle text-center"><?= $month_translate[$period_range['start_month']] ?></td>
                <td class="align-middle text-center"><?= $month_translate[$period_range['end_month']] ?></td>                
                <td class="align-middle text-center">
                    <div class="row justify-content-around">
                        <div class="col-3 text-center">
                            <a href="<?= $base_url ?>/views/forms/period_range_form.php?id=<?= $period_range['id'] ?>" class="btn btn-success" title="Editar">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div>                      
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
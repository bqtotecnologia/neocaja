<table class="table table-striped table-bordered datatable-date-3" style="width:100%">
    <thead>
        <tr>
            <th class="text-center" style="padding-right:15px !important;">Nº</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Valor</th>
            <th class="text-center">Fecha</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($global_var_history as $history) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $history['name'] ?></td>
                <td class="align-middle text-center"><?= $history['value'] ?></td>
                <td class="align-middle text-center"><?= $history['date'] ?></td>                
            </tr>
        <?php } ?>
    </tbody>
</table>
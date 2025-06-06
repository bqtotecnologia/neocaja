<table class="table table-striped table-bordered datatable-date-4" style="width:100%">
    <thead>
        <tr>
            <th class="text-center" style="padding-right:15px !important;">Nº</th>
            <th class="text-center">Cédula</th>
            <th class="text-center">Usuario</th>
            <th class="text-center">Acción</th>
            <th class="text-center">Fecha</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($binnacle as $event) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $event['cedula'] ?></td>
                <td class="align-middle text-center"><?= $event['name'] ?></td>
                <td class="align-middle text-center"><?= $event['action'] ?></td>
                <td class="align-middle text-center"><?= $event['created_at'] ?></td>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
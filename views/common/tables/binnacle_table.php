<table class="table table-striped col-12 datatable-date-5">
    <thead class="bg-theme text-white fw-bold h6">
        <tr>
            <th class="text-center align-middle">Nº</th>
            <th class="text-center align-middle">Cédula</th>
            <th class="text-center align-middle">Usuario</th>
            <th class="text-center align-middle">Dirección IP</th>
            <th class="text-center align-middle">Acción</th>
            <th class="text-center align-middle">Fecha</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($binnacle as $event) { ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center">
                    <?= $event['cedula'] === NULL ? 'No tiene' : $event['cedula'] ?>
                </td>
                <td class="align-middle text-center">
                    <?= $event['name'] === NULL ? 'Ninguno' : $event['name'] ?>
                </td>
                <td class="align-middle text-center fw-bold text-<?= $event['ip_address'] === NULL ? 'danger' : 'success' ?>">
                    <?= $event['ip_address'] === NULL ? 'Local' : $event['ip_address'] ?>
                </td>
                <td class="align-middle text-center"><?= $event['action'] ?></td>
                <td class="align-middle text-center"><?= $event['created_at'] ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
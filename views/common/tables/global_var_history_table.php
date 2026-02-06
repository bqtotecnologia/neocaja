<table class="table table-striped col-12 datatable-date-3">
    <thead class="bg-theme text-white fw-bold h6">
        <tr>
            <th class="text-center">Nº</th>
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
            <tr class="h6 <?= intval($history['current']) === 1 ? 'bg-notification' : '' ?>">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $history['name'] ?></td>
                <td class="align-middle text-center"><?= $history['value'] ?></td>
                <td class="align-middle text-center"><?= $history['date'] ?></td>                
            </tr>
        <?php } ?>
    </tbody>
</table>
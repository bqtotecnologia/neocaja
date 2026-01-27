<table class="table table-striped col-12 datatable-date-3">
    <thead class="bg-theme text-white fw-bold h6">
        <tr>
            <th class="text-center">Nº</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Tasa (Bs)</th>
            <th class="text-center">Fecha de la tasa</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($coin_history as $history) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $target_coin['name'] ?></td>
                <td class="align-middle text-center"><?= $history['price'] ?></td>
                <td class="align-middle text-center"><?= $history['created_at']  ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
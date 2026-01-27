<table class="table table-striped col-12 datatable-date-2">
    <thead class="bg-theme text-white fw-bold h6">
        <tr>
            <th class="text-center">Nº</th>
            <th class="text-center">Mensaje</th>
            <th class="text-center">Fecha</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($notifications as $notification) { ?>
            <tr class="h6" >
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center <?= $notification['viewed'] === '0' ? 'bg-notification' : '' ?>"><?= $notification['message'] ?></td>
                <td class="align-middle text-center"><?= $notification['created_at'] ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
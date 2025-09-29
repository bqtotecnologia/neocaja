<table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th class="text-center">Nº</th>
            <th class="text-center">Mensaje</th>
            <th class="text-center">Nueva</th>
            <th class="text-center">Fecha</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($notifications as $notification) { ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $notification['message'] ?></td>
                <td class="align-middle text-center">
                    <?php if ($notification['viewed'] === '0') { ?>
                        <i class="menu-icon fa fa-circle text-success"></i>
                    <?php } else { ?>
                        <i class="menu-icon fa fa-circle text-danger"></i>
                    <?php } ?>
                </td>
                <td class="align-middle text-center"><?= date('d/m/Y', strtotime($notification['created_at'])) ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
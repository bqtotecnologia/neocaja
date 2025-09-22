<table class="table table-striped table-bordered datatable-date-3" style="width:100%">
    <thead>
        <tr>
            <th class="text-center" style="padding-right:15px !important;">Nº</th>
            <th class="text-center">Número de cuenta</th>
            <th class="text-center">Rif/Cédula</th>
            <th class="text-center">Fecha de creación</th>
            <th class="text-center">Activo</th>
            <th class="text-center">Acción</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($transfers as $transfer) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $transfer['account_number'] ?></td>
                <td class="align-middle text-center"><?= $transfer['document_letter'] . $transfer['document_number'] ?></td>
                <td class="align-middle text-center"><?= $transfer['created_at'] ?></td>
                <td class="align-middle text-center">
                    <i class="fa fa-circle text-<?= intval($transfer['active']) === 1 ? 'success' : 'danger' ?>"></i>
                </td>
                <td class="">
                    <div class="row justify-content-around">
                        <div class="col-3 text-center">
                            <a href="<?= $base_url ?>/views/forms/transfer_form.php?id=<?= $transfer['id'] ?>" class="btn btn-success" title="Editar">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
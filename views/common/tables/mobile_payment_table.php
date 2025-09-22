<table class="table table-striped table-bordered datatable-date-3" style="width:100%">
    <thead>
        <tr>
            <th class="text-center" style="padding-right:15px !important;">Nº</th>
            <th class="text-center">Teléfono</th>
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
        <?php foreach($mobile_payments as $mobile_payment) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $mobile_payment['phone'] ?></td>
                <td class="align-middle text-center"><?= $mobile_payment['document_letter'] . $mobile_payment['document_number'] ?></td>
                <td class="align-middle text-center"><?= $mobile_payment['created_at'] ?></td>
                <td class="align-middle text-center">
                    <i class="fa fa-circle text-<?= intval($mobile_payment['active']) === 1 ? 'success' : 'danger' ?>"></i>
                </td>
                <td class="">
                    <div class="row justify-content-around">
                        <div class="col-3 text-center">
                            <a href="<?= $base_url ?>/views/forms/mobile_payment_form.php?id=<?= $mobile_payment['id'] ?>" class="btn btn-success" title="Editar">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<table class="table table-striped table-bordered datatable-date-2" style="width:100%">
    <thead>
        <tr>
            <th class="text-center" style="padding-right:15px !important;">Nº</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Fecha de creación</th>
            <th class="text-center">Acción</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($payment_method_types as $payment_method_type) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $payment_method_type['name'] ?></td>
                <td class="align-middle text-center"><?= $payment_method_type['created_at'] ?></td>
                <td class="">
                    <div class="row justify-content-around">
                        <div class="col-3 text-center">
                            <a href="<?= $base_url ?>/views/forms/payment_method_form.php?id=<?= $payment_method_type['id'] ?>" class="btn btn-success" title="Editar">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div>                      
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
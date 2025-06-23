<table class="table table-striped table-bordered datatable-date-5" style="width:100%">
    <thead>
        <tr>
            <th class="text-center" style="padding-right:15px !important;">Nº</th>
            <th class="text-center">Nº Control</th>
            <th class="text-center">Nº Factura</th>
            <th class="text-center">Cliente</th>
            <th class="text-center">Cédula</th>
            <th class="text-center">Fecha de creación</th>
            <th class="text-center">Acción</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($invoices as $invoice) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $invoice['control_number'] ?></td>
                <td class="align-middle text-center"><?= $invoice['invoice_number'] ?></td>
                <td class="align-middle text-center"><?= $invoice['account_fullname'] ?></td>
                <td class="align-middle text-center"><?= $invoice['cedula'] ?></td>
                <td class="align-middle text-center"><?= $invoice['created_at'] ?></td>
                <td class="">
                    <div class="row justify-content-around">
                        <div class="col-3 text-center">
                            <a href="<?= $base_url ?>/views/detailers/invoice_details.php?id=<?= $invoice['id'] ?>" class="btn btn-success" title="Ver detalles">
                                <i class="fa fa-eye"></i>
                            </a>
                        </div>                      
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
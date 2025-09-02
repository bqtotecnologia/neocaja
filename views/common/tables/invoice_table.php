<table class="table table-striped table-bordered datatable-date-5" style="width:100%">
    <thead>
        <tr>
            <th class="text-center" style="padding-right:15px !important;">Nº</th>
            <th class="text-center">Nº Factura</th>
            <th class="text-center">Nº Control</th>
            <th class="text-center">Cliente</th>
            <th class="text-center">Cédula</th>
            <th class="text-center">Fecha de creación</th>
            <th class="text-center">Razón</th>
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
                <td class="fw-bold align-middle text-center text-<?= intval($invoice['active']) === 0 ? 'danger' : 'success' ?>"><?= $invoice['invoice_number'] ?></td>
                <td class="align-middle text-center"><?= $invoice['control_number'] ?></td>
                <td class="align-middle text-center"><?= $invoice['account_fullname'] ?></td>
                <td class="align-middle text-center"><?= $invoice['cedula'] ?></td>
                <td class="align-middle text-center"><?= $invoice['created_at'] ?></td>
                <td class="align-middle text-center"><?= $invoice['reason'] ?></td>
                <td class="">                    
                    <div class="row justify-content-around">
                        <div class="m-0 p-2 mx-2">
                                <a href="<?= $base_url ?>/views/detailers/invoice_details.php?id=<?= $invoice['id'] ?>" class="text-white btn btn-success m-0" title="Ver detalles">
                                    <i class="fa fa-search table-icon"></i>
                                </a>
                            </div>
                        </div>  
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
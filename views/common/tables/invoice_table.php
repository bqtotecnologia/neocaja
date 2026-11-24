<table class="table table-striped col-12 datatable-date-5-datetime-8">
    <thead class="bg-theme text-white fw-bold h6">
        <tr>
            <th class="text-center">Nº</th>
            <th class="text-center">Nº Factura</th>
            <th class="text-center">Nº Control</th>
            <th class="text-center">Cliente</th>
            <th class="text-center">Cédula</th>
            <th class="text-center">Fecha</th>
            <th class="text-center">Estado</th>
            <th class="text-center">Observación</th>
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
                <td class="fw-bold align-middle text-center"><?= $invoice['invoice_number'] ?></td>
                <td class="align-middle text-center"><?= $invoice['control_number'] ?></td>
                <td class="align-middle text-center"><?= $invoice['account_fullname'] ?></td>
                <td class="align-middle text-center"><?= $invoice['cedula'] ?></td>
                <td class="align-middle text-center"><?= $invoice['rate_date'] ?></td>
                <td class="align-middle text-center fw-bold text-<?= $invoice['invoice_state'] === 'Activa' ? 'success' : 'danger' ?>">
                    <?= $invoice['invoice_state'] ?>
                </td>
                <td class="align-middle text-center"><?= $invoice['observation'] ?></td>
                <td class="align-middle text-center"><?= $invoice['created_at'] ?></td>
                <td class="align-middle">
                    <div class="d-flex justify-content-around align-items-center">
                        <div class="col px-1 text-center">
                            <a href="<?= $base_url ?>/views/detailers/invoice_details.php?id=<?= $invoice['id'] ?>" class="text-white btn btn-success m-0" title="Ver detalles">
                                <i class="fa fa-search table-icon"></i>
                            </a>
                        </div>  
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<table class="table table-striped col-12 datatable-date-8-datetime-9-10">
    <thead class="bg-theme text-white fw-bold h6">
        <tr>
            <th class="text-center align-middle">Nº</th>
            <th class="text-center align-middle">Nº Factura</th>
            <th class="text-center align-middle">Nº Control de la factura</th>
            <th class="text-center align-middle">Nº Nota de crédito</th>
            <th class="text-center align-middle">Nº Control de la nota de crédito</th>
            <th class="text-center align-middle">Cliente</th>
            <th class="text-center align-middle">Cédula</th>
            <th class="text-center align-middle">Observación</th>
            <th class="text-center align-middle">Fecha de la factura</th>
            <th class="text-center align-middle">Fecha de creación de la factura</th>
            <th class="text-center align-middle">Fecha de creación de la nota de crédito</th>
            <th class="text-center align-middle">Acción</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($credit_notes as $credit_note) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="fw-bold align-middle text-center"><?= $credit_note['invoice_number'] ?></td>
                <td class="align-middle text-center"><?= $credit_note['invoice_control_number'] ?></td>
                <td class="fw-bold align-middle text-center"><?= $credit_note['credit_note_number'] ?></td>
                <td class="align-middle text-center"><?= $credit_note['credit_note_control_number'] ?></td>
                <td class="align-middle text-center"><?= $credit_note['account_fullname'] ?></td>
                <td class="align-middle text-center"><?= $credit_note['cedula'] ?></td>
                <td class="align-middle text-center"><?= $credit_note['credit_note_reason'] ?></td>
                <td class="align-middle text-center"><?= $credit_note['rate_date'] ?></td>
                <td class="align-middle text-center"><?= $credit_note['invoice_created_at'] ?></td>
                <td class="align-middle text-center"><?= $credit_note['created_at'] ?></td>
                <td class="align-middle">
                    <div class="d-flex justify-content-around align-items-center">
                        <div class="col px-1 text-center">
                            <a href="<?= $base_url ?>/views/detailers/invoice_details.php?id=<?= $credit_note['invoice_id'] ?>" class="text-white btn btn-primary m-0" title="Ver factura">
                                <i class="fa fa-book table-icon"></i>
                            </a>
                        </div>

                        <div class="col px-1 text-center">
                            <a href="<?= $base_url ?>/views/exports/export_invoice_as_pdf.php?id=<?= $credit_note['invoice_id'] ?>&credit_note=1" target="_blank" class="text-white btn btn-success m-0" title="Ver detalles">
                                <i class="fa fa-search table-icon"></i>
                            </a>
                        </div>                          
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
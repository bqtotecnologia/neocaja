<table class="table table-striped table-bordered datatable-date-5" style="width:100%">
    <thead>
        <tr>
            <th class="text-center" style="padding-right:15px !important;">Nº</th>
            <th class="text-center">Cédula</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Método de pago</th>
            <th class="text-center">Monto (Bs.)</th>
            <th class="text-center">Fecha de creación</th>
            <th class="text-center">Estado</th>
            <th class="text-center">Ver</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($payments as $payment) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $payment['cedula'] ?></td>
                <td class="align-middle text-center"><?= $payment['fullname'] ?></td>
                <td class="align-middle text-center">
                    <?php
                     if($payment['payment_method_type'] === 'mobile_payment')
                        echo 'Pago móvil';
                    else if($payment['payment_method_type'] === 'transfer')
                        echo 'Transferencia';
                     ?>
                </td>
                <td class="align-middle text-center"><?= $payment['price'] ?></td>
                <td class="align-middle text-center"><?= $payment['created_at'] ?></td>
                <td class="align-middle text-center fw-bold <?= $payment['state'] ?>"><?= $payment['state'] ?></td>
                <td class="">
                    <div class="row justify-content-around">
                        <div class="col-3 text-center">
                            <a href="<?= $base_url ?>/views/detailers/payment_details.php?id=<?= $payment['id'] ?>" class="btn btn-success" title="Ver">
                                <i class="fa fa-search"></i>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
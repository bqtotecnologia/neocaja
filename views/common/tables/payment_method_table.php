<table class="table table-striped col-12 datatable-date-5">
    <thead class="bg-theme text-white fw-bold h6">
        <tr>
            <th class="text-center">Nº</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Requiere número de documento</th>
            <th class="text-center">Requiere banco</th>
            <th class="text-center">Requiere punto de venta</th>
            <th class="text-center">Fecha de creación</th>
            <?php if($_SESSION['neocaja_rol'] !== 'SENIAT') { ?>
                <th class="text-center">Acción</th>
            <?php } ?>
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
                <td class="align-middle text-center">
                    <i class="fa text-<?= intval($payment_method_type['document_number']) === 1 ? 'success fa-check' : 'danger fa-close' ?>"></i>                    
                </td>
                <td class="align-middle text-center">
                    <i class="fa text-<?= intval($payment_method_type['bank']) === 1 ? 'success fa-check' : 'danger fa-close' ?>"></i>                    
                </td>
                <td class="align-middle text-center">
                    <i class="fa text-<?= intval($payment_method_type['sale_point']) === 1 ? 'success fa-check' : 'danger fa-close' ?>"></i>                    
                </td>
                <td class="align-middle text-center"><?= $payment_method_type['created_at'] ?></td>
                <?php if($_SESSION['neocaja_rol'] !== 'SENIAT') { ?>
                    <td class="align-middle">
                        <div class="d-flex justify-content-around align-items-center">
                            <div class="col px-1 text-center">
                                <a href="<?= $base_url ?>/views/forms/payment_method_form.php?id=<?= $payment_method_type['id'] ?>" class="btn btn-success" title="Editar">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            </div>                      
                        </div>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
</table>
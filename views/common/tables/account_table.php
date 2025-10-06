<table class="table table-striped table-bordered datatable-date-6" style="width:100%">
    <thead>
        <tr>
            <th class="text-center" style="padding-right:15px !important;">Nº</th>
            <th class="text-center">Cédula</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Es estudiante</th>
            <th class="text-center">Beca</th>
            <th class="text-center">Empresa</th>
            <th class="text-center">Fecha de creación</th>
            <th class="text-center">Acción</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($accounts as $account) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $account['cedula'] ?></td>
                <td class="align-middle text-center"><?= $account['names'] . ' ' . $account['surnames'] ?></td>
                <td class="align-middle text-center">
                    <i class="fa fa-circle text-<?= $account['is_student'] === '1' ? 'success' : 'danger' ?>"></i>
                </td>
                <td class="align-middle text-center">
                    <?= $account['scholarship'] === NULL ? '<spann class="text-danger">NO tiene</span>' : ($account['scholarship'] . ' ' . intval($account['scholarship_coverage']) . '%') ?>
                </td>
                <td class="align-middle text-center">
                    <?= $account['company'] === NULL ? '<spann class="text-danger">NO aplica</span>' : $account['company'] ?>
                </td>
                <td class="align-middle text-center"><?= $account['created_at'] ?></td>
                <td class="">
                    <div class="row justify-content-around align-items-center">
                        <div class=" text-center">
                            <a href="<?= $base_url ?>/views/forms/account_form.php?id=<?= $account['id'] ?>" class="btn btn-success m-0" title="Editar">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div>

                        <div class=" text-center">
                            <a href="<?= $base_url ?>/views/detailers/account_details.php?id=<?= $account['id'] ?>" class="btn btn-info m-0" title="Ver detalles">
                                <i class="fa fa-search"></i>
                            </a>
                        </div>
                    </div>                    
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
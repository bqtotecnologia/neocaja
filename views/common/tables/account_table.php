<table class="table table-striped generic-datatable col-12">
    <thead class="bg-theme text-white fw-bold h6">
        <tr>
            <th class="text-center" style="padding-right:15px !important;">Nº</th>
            <th class="text-center">Cédula</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Es estudiante</th>
            <th class="text-center">Beca</th>
            <th class="text-center">Empresa</th>
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
                <td class="align-middle text-center fw-bold text-<?= $account['is_student'] === '1' ? 'success' : 'danger' ?>">
                    <?= $account['is_student'] === '1' ? 'Sí' : 'No' ?>
                </td>
                <td class="align-middle text-center">
                    <?= $account['scholarship'] === NULL ? '<spann class="text-danger fw-bold">No tiene</span>' : ($account['scholarship'] . ' ' . intval($account['scholarship_coverage']) . '%') ?>
                </td>
                <td class="align-middle text-center">
                    <?= $account['company'] === NULL ? '<spann class="text-danger fw-bold">No tiene</span>' : $account['company'] ?>
                </td>
                <td class="">
                    <div class="row justify-content-around align-items-center">
                        <?php if($_SESSION['neocaja_rol'] !== 'SENIAT') { ?>
                            <div class=" text-center">
                                <a href="<?= $base_url ?>/views/forms/account_form.php?id=<?= $account['id'] ?>" class="btn btn-success m-0" title="Editar">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            </div>
                        <?php } ?>

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
<table class="table table-striped table-bordered datatable-date-3-4" style="width:100%">
    <thead>
        <tr>
            <th class="text-center" style="padding-right:15px !important;">Nº</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Tasa más reciente</th>
            <th class="text-center">Fecha de la tasa</th>
            <th class="text-center">Fecha de creación</th>
            <th class="text-center">Activo</th>
            <th class="text-center">Acción</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($coins as $coin) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $coin['name'] ?></td>
                <td class="align-middle text-center"><?= $coin['price'] ?></td>
                <td class="align-middle text-center"><?= $coin['coin_created_at'] ?></td>
                <td class="align-middle text-center"><?= date('d/m/Y', strtotime($coin['price_created_at'])) ?></td>
                <td class="align-middle text-center">
                    <i class="fa fa-circle text-<?= $coin['active'] ? 'success' : 'danger' ?>"></i>
                </td>
                <td class="">
                    <div class="row justify-content-around">
                        <div class="col-3 text-center">
                            <a href="<?= $base_url ?>/views/forms/coin_form.php?id=<?= $coin['id'] ?>" class="btn btn-success" title="Editar">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div>

                        <div class="col-3 text-center">
                            <a href="<?= $base_url ?>/views/forms/update_coin_price.php?id=<?= $coin['id'] ?>" class="btn btn-warning" title="Cambiar tasa manualmente">
                                <i class="fa fa-refresh"></i>
                            </a>
                        </div>  

                        <div class="col-3 text-center">
                            <a href="<?= $base_url ?>/views/tables/coin_history.php?id=<?= $coin['id'] ?>" class="btn btn-info" title="Ver historial de tasas">
                                <i class="fa fa-list"></i>
                            </a>
                        </div>               
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
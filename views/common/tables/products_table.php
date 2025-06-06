<table class="table table-striped table-bordered datatable-date-3-4" style="width:100%">
    <thead>
        <tr>
            <th class="text-center" style="padding-right:15px !important;">Nº</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Precio($)</th>
            <th class="text-center">Fecha de creación</th>
            <th class="text-center">Fecha del precio</th>
            <th class="text-center">Acción</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($products as $product) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $product['name'] ?></td>
                <td class="align-middle text-center"><?= $product['price'] ?></td>
                <td class="align-middle text-center"><?= $product['product_created_at'] ?></td>
                <td class="align-middle text-center"><?= $product['price_created_at'] ?></td>

                <td class="">
                    <div class="row justify-content-around">
                        <div class="col-3 text-center">
                            <a href="<?= $base_url ?>/views/forms/product_form.php?id=<?= $product['id'] ?>" class="btn btn-success" title="Editar">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div>

                        <div class="col-3 text-center">
                            <a href="<?= $base_url ?>/views/tables/product_history.php?id=<?= $product['id'] ?>" class="btn btn-info" title="Ver historial de precios">
                                <i class="fa fa-list"></i>
                            </a>
                        </div>                        
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
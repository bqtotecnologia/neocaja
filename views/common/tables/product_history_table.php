<table id="products-history-table" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr ¡>
            <th class="text-center" style="padding-right:15px !important;">Nº</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Precio ($)</th>
            <th class="text-center">Fecha del precio</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($product_history as $history) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $target_product['name'] ?></td>
                <td class="align-middle text-center"><?= $history['price'] ?></td>
                <td class="align-middle text-center"><?= $history['created_at'] ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
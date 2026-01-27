<table class="table table-striped col-12 datatable-date-2">
    <thead class="bg-theme text-white fw-bold h6">
        <tr>
            <th class="text-center">Nº</th>
            <th class="text-center">Registros creados</th>
            <th class="text-center">Fecha de creación</th>
            <th class="text-center">Acción</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($generations as $generation) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $generation['created'] ?></td>
                <td class="align-middle text-center"><?= $generation['created_at'] ?></td>
                <td class="">
                    <div class="row justify-content-around">
                        <div class="col-6 text-center">
                            <a href="<?= $base_url ?>/views/tables/search_unknown_incomes_by_generation.php?id=<?= $generation['id'] ?>" class="btn btn-success" title="Ver registros">
                                <i class="fa fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
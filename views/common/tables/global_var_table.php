<table class="table table-striped col-12 datatable-date-3">
    <thead class="bg-theme text-white fw-bold h6">
        <tr>
            <th class="text-center">Nº</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Valor</th>
            <th class="text-center">Fecha</th>
            <th class="text-center">Acción</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($global_vars as $var) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $var['name'] ?></td>
                <td class="align-middle text-center"><?= $var['value'] ?></td>
                <td class="align-middle text-center"><?= $var['date'] ?></td>
                <td class="">
                    <div class="row justify-content-around">
                        <div class="col-3 text-center">
                            <a href="<?= $base_url ?>/views/forms/global_var_form.php?id=<?= $var['id'] ?>" class="btn btn-success" title="Editar">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div>   
                        
                        <div class="col-3 text-center">
                            <a href="<?= $base_url ?>/views/tables/global_var_history.php?id=<?= $var['id'] ?>" class="btn btn-info" title="Ver historial de valores">
                                <i class="fa fa-list"></i>
                            </a>
                        </div>   
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
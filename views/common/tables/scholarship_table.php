<table class="table table-striped col-12 datatable-date-2">
    <thead class="bg-theme text-white fw-bold h6">
        <tr>
            <th class="text-center">Nº</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Fecha de creación</th>
            <th class="text-center">Acción</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($scholarships as $scholarship) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $scholarship['name'] ?></td>
                <td class="align-middle text-center"><?= $scholarship['created_at'] ?></td>
                <td class="">
                    <div class="row justify-content-around">
                        <div class="col-3 text-center">
                            <a href="<?= $base_url ?>/views/forms/scholarship_form.php?id=<?= $scholarship['id'] ?>" class="btn btn-success" title="Editar">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div>                      
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
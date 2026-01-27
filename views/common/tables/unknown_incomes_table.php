<table class="table table-striped col-12 datatable-date-2-datetime-5">
    <thead class="bg-theme text-white fw-bold h6">
        <tr>
            <th class="text-center">Nº</th>
            <th class="text-center">Referencia</th>
            <th class="text-center">Fecha</th>
            <th class="text-center">Monto</th>
            <th class="text-center">Cliente</th>
            <th class="text-center">Fecha de creación</th>
            <th class="text-center">Acción</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($incomes as $income) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $income['ref'] ?></td>
                <td class="align-middle text-center"><?= $income['date'] ?></td>
                <td class="align-middle text-center"><?= $income['price'] ?></td>
                <td class="align-middle text-center">
                    <?php 
                        $display = '';
                        if($income['cedula'] !== null)
                            $display = $income['surnames'] . ' ' . $income['names'] . ' (' . $income['cedula'] . ')';

                        echo $display;
                    ?></td>
                <td class="align-middle text-center"><?= $income['created_at'] ?></td>
                <td class="">
                    <div class="row justify-content-around">
                        <div class="col-6 text-center">
                            <a href="<?= $base_url ?>/views/forms/unknown_income_form.php?id=<?= $income['id'] ?>" class="btn btn-success" title="Editar">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
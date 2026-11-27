<table class="table table-striped generic-datatable col-12">
    <thead class="bg-theme text-white fw-bold h6">
        <tr>
            <th class="text-center" style="padding-right:15px !important;">Nº</th>
            <th class="text-center">Letra de cédula</th>
            <th class="text-center">Cédula</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Periodo</th>
            <th class="text-center">Motivo</th>
            <th class="text-center">Acción</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($absolutions as $absolution) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <td class="align-middle text-center"><?= $absolution['cedula_letter'] ?></td>
                <td class="align-middle text-center"><?= $absolution['cedula'] ?></td>
                <td class="align-middle text-center"><?= $absolution['fullname'] ?></td>
                <td class="align-middle text-center"><?= $periodNames[$absolution['period']]['nombreperiodo'] ?></td>
                <td class="align-middle text-center"><?= $absolution['reason'] ?></td>
                <td class="align-middle">
                    <div class="d-flex justify-content-around align-items-center">
                        <?php if($_SESSION['neocaja_rol'] !== 'SENIAT') { ?>
                            <div class="col px-1 text-center">
                                <a href="<?= $base_url ?>/views/forms/absolution_form.php?id=<?= $absolution['id'] ?>" class="btn btn-success m-0" title="Editar">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            </div>
                        <?php } ?>
                    </div>                    
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
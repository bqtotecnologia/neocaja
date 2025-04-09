<table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr class="text-center">
            <th class="" style="padding-right:15px !important;">Nº</th>
            <th class="">Docente</th>
            <th class="">Cédula del evaluador</th>
            <th class="">Carrera del evaluador</th>
            <th class="">Observación</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($observaciones as $observacion) {  
            $docente = $siacad->GetDocenteById($observacion['iddocente']);
            $student = $siacad->GetEstudianteByCedula($observacion['cedula_evaluador']);
            ?>
            <tr class="h6">
                <td class="align-middle"><?php echo $count; $count++; ?></td>
                <td class="align-middle"><?= $docente['nombres'] . ' ' . $docente['apellidos'] ?></td>
                <td class="align-middle"><?= $docente['cedula'] ?></td>
                <td class="align-middle"><?php if($student === false) { echo 'Hecha por coordinador'; } else { echo $student['carrera']; } ?></td>
                <td class="align-middle"><?= $observacion['observacion'] ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
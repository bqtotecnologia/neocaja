<?php
    $type_translator = [
        'student' => 'Estudiante',
        'teacher' => 'Docente',
        'coord' => 'Coordinador'
    ];

    $element_columns = [
        'categoria' => ['Categoría'],
        'dimension' => ['Dimensión', 'Categoría'],
        'indicador' => ['Indicador', 'Dimensión', 'Tipo'],
        'enunciado' => ['Enunciado', 'Indicador', 'Corte', 'Grupo criterio', 'Activo'],
        'grupo_criterio' => ['Grupo criterio'],
        'criterio' => ['Criterio', 'Grupo criterio', 'Peso']
    ];

    $current_columns = $element_columns[$element];
?>

<table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr class="text-center">
            <th class="text-center" style="padding-right:15px !important;">Nº</th>
            <?php foreach($current_columns as $column) { ?>
                <th><?= $column ?></th>
            <?php } ?>
            <th class="">Acción</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 1;
        ?>
        <?php foreach($evaluation_elements as $eva_element) {  ?>
            <tr class="h6">
                <td class="align-middle text-center"><?php echo $count; $count++; ?></td>
                <?php foreach($eva_element as $key => $value) { if($key === 'id') continue; ?>
                    <td class="align-middle">
                        <?php
                        if($value === '' || $value === null) 
                            echo '<span class="text-danger">No aplica</span>';
                        else{
                            if($key === 'tipo')
                                echo $type_translator[$value];
                            else if ($key === 'activo'){
                                if($value === false)
                                    echo '<span class="text-danger">No</span>';
                                else 
                                    echo '<span class="text-success">Sí</span>';
                            }
                            else 
                                echo $value;
                        }
                        ?>
                    </td>
                <?php } ?>
                <td class="d-flex justify-content-center align-middle">
                    <div class="">
                        <div class="col-3 text-center">
                            <a href="add_<?= $element ?>.php?edit=1&id=<?= $eva_element['id'] ?>" class="btn btn-success">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
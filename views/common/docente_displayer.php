<div class="row flex-row justify-content-around align-items-start text-center">
    <div class="row col-12 col-md-6">
        <h1 class="col-12 text-center fw-bold">
            <?= $docente['nombres'] . ' ' . $docente['apellidos'] ?>
        </h1>
        <?php if($materias !== false) { ?>
            <ul class="col-12 list-unstyled">
                <?php foreach($materias as $materia) { ?>
                    <li class="w-100 text-center">
                        <?= $materia['nombremateria'] ?>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>

    <?php 
        $width = $materias !== false ? '75' : '100'; 
        //$columns = $materias !== false ? '3' : '6';
    ?>
    <div class="col-8 col-md-6">
    <?php
        $image_url = '../../sisnom/images/carnets/' . $docente['cedula'] . '.jpg';
        if(!file_exists($image_url))
            $image_url = '../../sisnom/images/carnets/interrogante.png';
    ?>
        <img 
        class="w-<?= $width ?> shadowed"
        src="<?= $image_url ?>" 
        style="max-width:300px !important;"
        alt="<?= $docente['nombres'] ?> photo"
        >
    </div>
</div>
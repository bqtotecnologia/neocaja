<?php include 'common/header.php'; ?>

<?php 
$errors = [
    'Ocurrió un error (Elemento inexistente)',
    'No se encontró el elemento solicitado',
    'Información por GET incorrecta',
    'Ocurrió un error inesperado'
];

$hyperlink_style = 'col-12 text-white text-center text-decoration-none p-0 h5';
$button_style = 'col-8 btn btn-info m-1 shadowed';

?>

<div class="row justify-content-around text-center">

    <div class="col-12 row m-0 p-2">
        <h1 class="col-12 m-0 p-0 h1">
                Gestión de criterios de evaluación
        </h1>
    </div>

    <?php if(isset($_GET['error'])) { ?>
        <h3 class="text-center col-12 mb-4 fw-bold h3 text-danger">
            <?= $errors[$_GET['error']] ?>
        </h3>
    <?php } ?>

    <div class="col-12 row m-0 p-2 col-md-5">
        <div class="col-12 row m-0 p-0 x_panel">
            <h3 class="col-12 h3 mb-3">
                Estructura de criterios
            </h3>
            <div class="col-12 row m-0 p-0 justify-content-center">
                <a class="<?= $hyperlink_style ?>" href="evaluation_element_list.php?element=categoria">
                    <button class="<?= $button_style ?>">
                        Categorías
                    </button>
                </a>
            </div>
            <div class="col-12 row m-0 p-0 justify-content-center">
                <a class="<?= $hyperlink_style ?>" href="evaluation_element_list.php?element=dimension">
                    <button class="<?= $button_style ?>">
                        Dimensiones
                    </button>
                </a>
            </div>
            <div class="col-12 row m-0 p-0 justify-content-center">
                <a class="<?= $hyperlink_style ?>" href="evaluation_element_list.php?element=indicador">
                    <button class="<?= $button_style ?>">
                        Indicadores
                    </button>
                </a>
            </div>
            <div class="col-12 row m-0 p-0 justify-content-center">
                <a class="<?= $hyperlink_style ?>" href="evaluation_element_list.php?element=enunciado">
                    <button class="<?= $button_style ?>">
                        Enunciados
                    </button>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 row m-0 p-2 col-md-5">
        <div class="col-12 row m-0 p-0 x_panel">
            <h3 class="col-12 h3 mb-3">
                Gestión de criterios
            </h3>
            <div class="col-12 row m-0 p-0 justify-content-center">
                <a class="<?= $hyperlink_style ?>" href="evaluation_element_list.php?element=grupo_criterio">
                    <button class="<?= $button_style ?>">
                        Grupo criterio
                    </button>
                </a>
            </div>
            <div class="col-12 row m-0 p-0 justify-content-center">
                <a class="<?= $hyperlink_style ?>" href="evaluation_element_list.php?element=criterio">
                    <button class="<?= $button_style ?>">
                        Criterios
                    </button>
                </a>
            </div>
        </div>
    </div>
</div>
<?php include 'common/footer.php'; ?>
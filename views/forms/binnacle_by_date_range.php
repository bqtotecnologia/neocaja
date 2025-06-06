<?php
$admitted_user_types = ['Super', 'Supervisor'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

if(!empty($_POST)){
    if(strtotime($_POST['start_date']) > strtotime($_POST['end_date'])){
        header("Location: $base_url/views/forms/binnacle_by_date_range.php?error=La fecha final debe ser posterior o igual a la fecha inicial");
        exit;
    }
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';

$fields = [
    [
        'name' => 'start_date',
        'display' => 'Fecha de inicio',
        'id' => 'start_date',
        'type' => 'date',
        'placeholder' => '',
        'size' => 6,
        'required' => true,
        'value' => empty($_POST) ? '' : $_POST['start_date']
    ],
    [
        'name' => 'end_date',
        'display' => 'Fecha de fin',
        'id' => 'end_date',
        'type' => 'date',
        'placeholder' => '',
        'size' => 6,
        'required' => true,
        'value' => empty($_POST) ? '' : $_POST['end_date']
    ],
];

$formBuilder = new FormBuilder(
    '',    
    'POST',
    'Buscar bitácora por rango de fechas',
    'Buscar',
    '',
    $fields,
    false
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../panel.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>


    <?php if(!empty($_POST)) { ?>
        <?php 
            include_once '../../models/binnacle_model.php';
            $binnacle_model = new BinnacleModel();
            $binnacle = $binnacle_model->GetBinnacleByDateRange($_POST['start_date'], $_POST['end_date']);

        ?>
        <div class="col-12 row justify-content-center px-4">
            <div class="col-12 row justify-content-center x_panel">
                <?php 
                include '../common/tables/binnacle_table.php'; 
                ?>
            </div>
        </div>
    <?php } ?>
</div>

<?php include_once '../common/footer.php'; ?>
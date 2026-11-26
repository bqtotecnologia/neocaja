<?php
$admitted_user_types = ['Super', 'Administrador'];
include_once '../../utils/validate_user_type.php';

$edit = isset($_GET['id']);
$form = true;
$error = '';

if($edit){
    include_once '../../utils/Validator.php';
    $id = Validator::ValidateRecievedId();

    if(is_string($id)){
        $error = $id;
    }  
}

if($error === '' && $edit){
    include_once '../../models/period_range_model.php';
    $period_range_model = new PeriodRangeModel();
    $target_period_range = $period_range_model->GetPeriodRangeById($id);
    if($target_period_range === false){
        $error = 'No se encontró el rango de meses deseado';
    }
}

if($error !== ''){
    header("Location: $base_url/views/tables/search_period_range.php?error=$error");
    exit;
}

include_once '../../utils/months_data.php';
include_once '../../models/siacad_model.php';
$siacad = new SiacadModel();

$periods = $siacad->GetPeriods();
$display_periods = [];

foreach($periods as $period){
    $to_add = [
        'display' => $period['nombreperiodo'],
        'value' => $period['idperiodo']
    ];
    array_push($display_periods, $to_add);
}

$display_months = [];
foreach($month_translate as $number => $name){
    $to_add = [
        'display' => $name,
        'value' => $number
    ];
    array_push($display_months, $to_add);
}

include_once '../common/header.php';
include_once '../../fields_config/period_ranges.php';
include_once '../../utils/FormBuilder.php';

$formBuilder = new FormBuilder(
    '../../controllers/handle_period_range.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nuevo') . ' rango de meses de periodo',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $periodRangeFields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_period_range.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>
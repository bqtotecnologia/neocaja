<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';

$error = '';
$target_period_range = false;

if(empty($_POST)){
    $error = 'POST vacío';
}

$edit = isset($_POST['id']);
$form = false;

if($error === '' && $edit){
    $id = Validator::ValidateRecievedId('id', 'POST');
    if(is_string($id))
        $error = $id;
}

if($error === ''){
    include_once '../models/period_range_model.php';
    $period_range_model = new PeriodRangeModel();

    if($edit){
        $target_period_range = $period_range_model->GetPeriodRangeById($id);
        if($target_period_range === false)
            $error = 'No se encontró el rango de meses';
    }
}

if($error === ''){
    include_once '../fields_config/period_ranges.php';
    $cleanData = Validator::ValidatePOSTFields($periodRangeFields);
    if(is_string($cleanData))
        $error = $cleanData;
}

if($error === ''){
    include_once '../models/siacad_model.php';
    $siacad_model = new SiacadModel();

    $target_period = $siacad_model->GetPeriodoById($cleanData['period']);
    if($target_period === false)
        $error = 'Periodo no encontrado';
}

if($error === ''){
    $exists = $period_range_model->GetPeriodRangeOfPeriod($target_period['idperiodo']);
    if($exists !== false){
        if($edit){
            if($exists['id'] !== $target_period_range['id'])
                $error = 'El periodo seleccionado ya posee un rango de meses registrado';
        }
        else
            $error = 'El periodo seleccionado ya posee un rango de meses registrado';
    }
}  

// Creating / updating the period range
if($error === ''){    
    if($edit){
        $updated = $period_range_model->UpdatePeriodRange($cleanData['id'], $cleanData);
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar el rango de meses del periodo';
    }
    else{
        $created = $period_range_model->CreatePeriodRange($cleanData);
        if($created === false)
            $error = 'Hubo un error al intentar registrar el rango de meses del periodo';
    }
}

// Managing feedback message and binnacle
if($error === ''){
    if($edit){        
        $message = 'Rango de meses actualizado correctamente';

        $periodChanged = intval($cleanData['period']) !== intval($target_period_range['period']);
        $startMonthChanged = intval($cleanData['start_month']) !== intval($target_period_range['start_month']);
        $endMonthChanged = intval($cleanData['end_month']) !== intval($target_period_range['end_month']);

        $action = 'Actualizó el rango de meses del periodo ' . $target_period['nombreperiodo'];

        if($periodChanged)
            $action .= '. Al periodo ' . $target_period['nombreperiodo'];

        if($startMonthChanged)
            $action .= '. Al mes de inicio ' . $cleanData['start_month'];

        if($endMonthChanged)
            $action .= '. Al mes de fin ' . $cleanData['end_month'];
    }
    else{
        $created = $period_range_model->GetPeriodRangeOfPeriod($cleanData['period']);
        $message = 'Rango de meses registrado correctamente';
        $action = 'Registró el rango de meses de ' . $cleanData['start_month'] . '-' . $cleanData['end_month'] . ' para el periodo '  . $target_period['nombreperiodo'];
    }
    $period_range_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){    
    if($edit)
        header("Location: $base_url/views/forms/period_range_form.php?message=$message&id=" . $cleanData['id']);
    else
        header("Location: $base_url/views/forms/period_range_form.php?message=$message&id=" . $created['id']);
}
else{
    if($edit){
        if($target_period_range === false)
            header("Location: $base_url/views/tables/search_period_range.php?error=$error");
        else
            header("Location: $base_url/views/forms/period_range_form.php?error=$error&id=" . $target_period_range['id']);
    }
    else
        header("Location: $base_url/views/forms/period_range_form.php?error=$error");
}

exit;
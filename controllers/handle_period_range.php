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
    $cleanData['active'] = isset($_POST['active']) ? '1' : '0';

    if($edit){
        $updated = $period_range_model->UpdateBank($cleanData['id'], $cleanData);
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar el banco';
    }
    else{
        $created = $period_range_model->CreateBank($cleanData);
        if($created === false)
            $error = 'Hubo un error al intentar registrar el banco';
    }
}

// Managing feedback message and binnacle
if($error === ''){
    if($edit){        
        $message = 'Banco actualizado correctamente';

        $activeChanged = $cleanData['active'] !== $target_period_range['active'];
        $nameChanged = $cleanData['name'] !== $target_period_range['name'];

        $action = 'Actualizó el banco ' . $target_period_range['name'];
        if($nameChanged)
            $action .= '. Al nombre ' . $cleanData['name'];

        if($activeChanged)
            $action .= '. Al estado activo ' . (intval($cleanData['active']) === 1 ? 'Si' : 'No');
    }
    else{
        $created = $period_range_model->GetBankByName($cleanData['name']);
        $message = 'Banco registrado correctamente';
        $action = 'Creo el banco ' . $cleanData['name'];
    }
    $period_range_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){    
    if($edit)
        header("Location: $base_url/views/forms/bank_form.php?message=$message&id=" . $cleanData['id']);
    else
        header("Location: $base_url/views/forms/bank_form.php?message=$message&id=" . $created['id']);
}
else{
    if($edit){
        if($target_period_range === false)
            header("Location: $base_url/views/tables/search_bank.php?error=$error");
        else
            header("Location: $base_url/views/forms/bank_form.php?error=$error&id=" . $target_period_range['id']);
    }
    else
        header("Location: $base_url/views/forms/bank_form.php?error=$error");
}

exit;
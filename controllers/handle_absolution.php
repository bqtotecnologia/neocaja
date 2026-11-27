<?php
$admitted_user_types = ['Cajero', 'Super', 'Administrador'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';

$error = '';
$target_absolution = false;

if(empty($_POST)){
    $error = 'POST vacío';
}

$edit = isset($_POST['id']);

if($error === '' && $edit){
    $id = Validator::ValidateRecievedId('id', 'POST');
    if(is_string($id))
        $error = $id;
}

if($error === ''){
    include_once '../models/absolution_model.php';
    $absolution_model = new AbsolutionModel();

    if($edit){
        $target_absolution = $absolution_model->GetAbsolutionById($id);
        if($target_absolution === false)
            $error = 'Absolución no encontrada';
    }
}

if($error === ''){
    $account = Validator::ValidateRecievedId('account', 'POST');
    if(is_string($account))
        $error = $account;
}

if($error === ''){
    include_once '../models/account_model.php';
    $account_model = new AccountModel();

    $target_account = $account_model->GetAccount($account);
    if($target_account === false)
        $error = 'Cliente no encontrado';
}

if($error === ''){
    $new_concepts = $_POST['concepts'];
    if(count($new_concepts) < 1)
        $error = 'No se recibieron conceptos para la absolución';
}

if($error === ''){
    foreach($new_concepts as $concept){
        if($concept !== 'FOC'){
            if(intval($concept) > 12 || intval($concept) < 1)
                $error = "Mes $concept inválido";
        }
    }
}

if($error === ''){
    $reason = $_POST['reason'];
    if(strlen($reason) > 255)
        $error = 'El motivo de la absolvencia supera el límite de caracteres (255)';
}

if($error === ''){
    if(Validator::HasSuspiciousCharacters($reason, false))
        $error = 'El motivo de la absolvencia contiene caracteres sospechosos';
}

if($error === ''){
    $period = Validator::ValidateRecievedId('period', 'POST');
    if(is_string($period))
        $error = $period;
}

if($error === ''){
    include_once '../models/siacad_model.php';
    $siacad = new SiacadModel();

    $target_period = $siacad->GetPeriodoById($period);
    if($target_period === false)
        $error = 'Periodo no encontrado';
}

if($error === ''){
    $exists = $absolution_model->GetAbsolutionOfStudentOfPeriod($target_account['id'], $target_period['idperiodo']);

    if($exists !== false){
        if($edit){
            if(intval($exists['id']) !== intval($target_absolution['id']))
                $error = 'El estudiante ya tiene una absolución para el periodo ' . $target_period['nombreperiodo'];
        }
        else{
            $error = 'El estudiante ya tiene una absolución para el periodo ' . $target_period['nombreperiodo'];
        }
    }
}

$require_delete = false;
// Creating the absolution
if($error === ''){
    $data = [
        'account' => $target_account['id'],
        'period' => $target_period['idperiodo'],
        'reason' => $reason
    ];

    if($edit){
        $updated = $absolution_model->UpdateAbsolution($target_absolution['id'], $data);
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar la absolución';
    }
    else{    
        $created = $absolution_model->CreateAbsolution($data);
        if($created === false)
            $error = 'Hubo un error al intentar crear la absolución al estudiante';
        else
            $target_absolution = $absolution_model->GetAbsolutionOfStudentOfPeriod($target_account['id'], $target_period['idperiodo']);
    }
}

// Adding the concepts to the absolution
if($error === ''){
    $old_concepts = $absolution_model->GetAbsolutionConcepts($target_absolution['id']);
    $updated = $absolution_model->UpdateAbolutionConcepts($target_absolution['id'], $new_concepts);
    if($updated === false)
        $error = 'Hubo un error al asignar los conceptos de la absolución';
}


// Managing feedback message and binnacle
if($error === ''){
    if($edit){        
        $message = 'Absolución del estudiante actualizada correctamente';

        $accountChanged = intval($target_account['id']) !== intval($target_absolution['account_id']);
        $periodChanged = intval($target_period) !== intval($target_absolution['period']);
        $reasonChanged = $reason !== $target_absolution['reason'];

        $conceptsChanged = '';
        foreach($new_concepts as $concept){
            if(!in_array($concept, $old_concepts))
                $conceptsChanged .= '+' . $concept . ', ';
        }

        foreach($old_concepts as $concept){
            if(!in_array($concept, $new_concepts))
                $conceptsChanged .= '-' . $concept . ', ';
        }

        $action = 'Actualizó la absolución del estudiante ' . $target_absolution['names'] . ' ' . $target_absolution['surnames'] . ' de cédula: ' . $target_absolution['cedula_letter'] . $target_absolution['cedula'];

        if($accountChanged)
            $action .= '. Al estudiante ' . $target_account['cedula_letter'] . $target_account['cedula'];

        if($periodChanged)
            $action .= '. Al periodo ' . $target_period['nombreperiodo'];

        if($reasonChanged)
            $action .= '. Al motivo ' . $reason;

        if($conceptsChanged !== ''){
            $action .= '. Añadió o removió los siguientes conceptos: ' . $conceptsChanged;
        }
    }
    else{
        $message = 'Absolución del estudiante registrada correctamente';
        $action = 'Creo el absolución del estudiante ' . $target_account['names'] . ' ' . $target_account['surnames'] . ' de cédula: ' . $target_account['cedula_letter'] . $target_account['cedula'];
        $action .= '. Y los siguientes conceptos: ';
        foreach($new_concepts as $concept){
            $action .= $concept . ', ';            
        }
        $action = trim($action, ', ');
    }
    $absolution_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}


if($error === ''){    
    if($edit)
        header("Location: $base_url/views/forms/absolution_form.php?message=$message&id=" . $target_absolution['id']);
    else
        header("Location: $base_url/views/forms/absolution_form.php?message=$message&id=" . $target_absolution['id']);
}
else{
    if($edit){
        if($target_absolution === false)
            header("Location: $base_url/views/tables/search_absolutions.php?error=$error");
        else
            header("Location: $base_url/views/forms/absolution_form.php?error=$error&id=" . $target_absolution['id']);
    }
    else
        header("Location: $base_url/views/forms/absolution_form.php?error=$error");
}

exit;
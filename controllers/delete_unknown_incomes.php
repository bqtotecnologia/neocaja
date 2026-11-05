<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';

include_once '../models/unknown_incomes_model.php';
$unknown_income_model = new UnknownIncomesModel();

$error = '';
$target_generation = false;

$id = Validator::ValidateRecievedId('id', 'POST');
if(is_string($id))
    $error = $id;

if($error === ''){
    $target_generation = $unknown_income_model->GetUnknownIncomesGeneration($id);
    if($target_generation === false)
        $error = 'Generación de ingresos no identificados no encontrado';
}

if($error === ''){
    $remote_payments = $unknown_income_model->GetUnknownIncomesOfGenerationWithRemotePayments($id);
    if(count($remote_payments) > 0)
        $error = 'No se puede borrar la generación porque uno o más pagos remotos están relacionados con uno o más ingresos no identificados';
}

if($error === ''){
    $deleted = $unknown_income_model->DeleteIncomesGeneration($target_generation['id']);
    if($deleted === false)
        $error = 'Hubo un error al intentar borrar la generación de ingresos no identificados';
}

if($error === ''){
    $binnacle = "Borró la generación de ingresos no identificados creada el " . $target_generation['created_at'];
    $unknown_income_model->CreateBinnacle($_SESSION['neocaja_id'], $binnacle);
    header("Location: ../views/tables/search_unknown_income_generations.php?message=Ingresos no identificados borrados exitosamente");
}
else{
    if($target_generation === false)
        header("Location: ../views/detailers/search_unknown_income_generations.php?error=$error");
    else
        header("Location: ../views/tables/search_unknown_incomes_by_generation.php?error=$error&id=" . $target_generation['id']);
}
exit;
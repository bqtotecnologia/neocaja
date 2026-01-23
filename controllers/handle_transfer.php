<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';

$error = '';
$target_transfer = false;

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
    include_once '../models/transfers_model.php';
    $transfer_model = new TransfersModel();

    if($edit){
        $target_transfer = $transfer_model->GetTransfer($id);
        if($target_transfer === false)
            $error = 'Cuenta de transferencias no encontrada';
    }
}

if($error === ''){
    include_once '../fields_config/transfers.php';
    $cleanData = Validator::ValidatePOSTFields($transferFields);
    if(is_string($cleanData))
        $error = $cleanData;
}

if($error === ''){
    include_once '../models/bank_model.php';
    $bank_model = new BankModel();

    $target_bank = $bank_model->GetBankById($cleanData['bank']);
    if($target_bank === false)
        $error = 'Banco no encontrado';
}

// Creating / updating the transfer
if($error === ''){
    $cleanData['active'] = intval(isset($_POST['active']));
    if($edit){
        $updated = $transfer_model->SimpleUpdate('transfers', $cleanData, $cleanData['id']);
        
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar la cuenta de transferencias';
    }
    else{
        $created = $transfer_model->CreateTransfer($cleanData);
        if($created === false)
            $error = 'Hubo un error al intentar registrar la cuenta de transferencias';
    }
}

// Managing feedback message and binnacle
if($error === ''){    
    if($edit){        
        $message = 'Cuenta de transferencias actualizada correctamente';

        $accountNumberChanged = $cleanData['account_number'] !== $target_transfer['account_number'];
        $letterChanged = $cleanData['document_letter'] !== $target_transfer['document_letter'];
        $numberChanged = $cleanData['document_number'] !== $target_transfer['document_number'];
        $bankChanged = $cleanData['bank'] !== $target_transfer['bank'];
        $activeChanged = intval($cleanData['active']) !== intval($target_transfer['active']);

        $action = 'Actualizó la cuenta de transferencias de id ' . $target_transfer['id'];
        if($accountNumberChanged)
            $action .= '. Al número de cuenta ' . $cleanData['account_number'];

        if($letterChanged)
            $action .= '. A la letra de documento ' . $cleanData['document_letter'];

        if($numberChanged)
            $action .= '. Al número de documento ' . $cleanData['document_number'];

        if($bankChanged)
            $action .= '. Al banco ' . $cleanData['bank'];

        if($activeChanged)
            $action .= '. Al estado activo ' . $cleanData['active'];
    }
    else{
        $message = 'Cuenta de transferencias registrada correctamente';
        $action = 'Creo la cuenta de transferencias de numero de cuenta' . $cleanData['account_number'] . ' con el documento ' . $cleanData['document_letter'] . '-' . $cleanData['document_number'] . ' con el banco ' . $cleanData['bank'];
    }
    $transfer_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){    
    if($edit)
        header("Location: $base_url/views/forms/transfer_form.php?message=$message&id=" . $cleanData['id']);
    else
        header("Location: $base_url/views/forms/transfer_form.php?message=$message&id=" . $created['id']);
}
else{
    if($edit){
        if($target_transfer === false)
            header("Location: $base_url/views/tables/search_transfers.php?error=$error");
        else
            header("Location: $base_url/views/forms/transfer_form.php?error=$error&id=" . $target_transfer['id']);
    }
    else
        header("Location: $base_url/views/forms/transfer_form.php?error=$error");
}

exit;
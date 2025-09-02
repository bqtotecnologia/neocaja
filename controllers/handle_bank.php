<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';

include_once '../utils/base_url.php';
include_once '../utils/Validator.php';

$error = '';
$target_bank = false;

if(empty($_POST)){
    $error = 'POST vacío';
}

$fields_config = [
    'name' => [
        'min' => 1,
        'max' => 255,
        'required' => true,
        'type' => 'string',
        'suspicious' => true,
    ],
];

$result = Validator::ValidatePOSTFields($fields_config);
if(is_string($result))
    $error = $result;
else
    $cleanData = $result;

$edit = isset($_POST['id']);

if($error === ''){
    include_once '../models/bank_model.php';
    $bank_model = new BankModel();

    if($edit){
        $target_bank = $bank_model->GetBankById($cleanData['id']);
        if($target_bank === false)
            $error = 'Banco no encontrado';
    }
}

if($error === ''){
    if($edit){
        if($target_bank['id'] !== $cleanData['id'] && $target_bank['name'] === $cleanData['name'])
            $error = 'El nombre ingresado ya está registrado';
    }
}  

// Creating / updating the bank
if($error === ''){    
    $cleanData['active'] = isset($_POST['active']) ? '1' : '0';

    if($edit){
        $updated = $bank_model->UpdateBank($cleanData['id'], $cleanData);
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar el banco';
    }
    else{
        $created = $bank_model->CreateBank($cleanData);
        if($created === false)
            $error = 'Hubo un error al intentar registrar el banco';
    }
}

// Managing feedback message and binnacle
if($error === ''){
    if($edit){        
        $message = 'Banco actualizado correctamente';

        $activeChanged = $cleanData['active'] !== $target_bank['active'];
        $nameChanged = $cleanData['name'] !== $target_bank['name'];

        $action = 'Actualizó el banco ' . $target_bank['name'];
        if($nameChanged)
            $action .= '. Al nombre ' . $cleanData['name'];

        if($activeChanged)
            $action .= '. Al estado activo ' . ($cleanData['active'] === '1' ? 'Si' : 'No');
    }
    else{
        $created = $bank_model->GetBankByName($cleanData['name']);
        $message = 'Banco registrado correctamente';
        $action = 'Creo el banco ' . $cleanData['name'];
    }
    $bank_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){    
    if($edit)
        header("Location: $base_url/views/forms/bank_form.php?message=$message&id=" . $cleanData['id']);
    else
        header("Location: $base_url/views/forms/bank_form.php?message=$message&id=" . $created['id']);
}
else{
    if($edit){
        if($target_bank === false)
            header("Location: $base_url/views/tables/search_bank.php?error=$error");
        else
            header("Location: $base_url/views/forms/bank_form.php?error=$error&id=" . $target_bank['id']);
    }
    else
        header("Location: $base_url/views/forms/bank_form.php?error=$error");
}

exit;
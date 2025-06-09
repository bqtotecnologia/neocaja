<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';

include_once '../utils/base_url.php';
include_once '../utils/Validator.php';

$error = '';
$target_company = false;

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
    'rif_letter' => [
        'min' => 1,
        'max' => 1,
        'required' => true,
        'type' => 'string',
        'suspicious' => true,
    ],
    'rif_number' => [
        'min' => 9,
        'max' => 9,
        'required' => true,
        'type' => 'string',
        'suspicious' => true,
    ],
    'address' => [
        'min' => 1,
        'max' => 1000,
        'required' => true,
        'type' => 'string',
        'suspicious' => false,
    ],
];

$result = Validator::ValidatePOSTFields($fields_config);
if(is_string($result))
    $error = $result;
else
    $cleanData = $result;

$edit = isset($_POST['id']);

if($error === ''){
    include_once '../models/company_model.php';
    $company_model = new CompanyModel();

    if($edit){
        $target_company = $company_model->GetCompany($cleanData['id']);
        if($target_company === false)
            $error = 'Empresa no encontrada';
    }
    else{
        $target_company = $company_model->GetCompanyByRif($cleanData['rif_letter'], $cleanData['rif_number']);
        if($target_company !== false)
            $error = 'El rif de la empresa está repetido';
    }   
}

if($error === ''){
    if($edit){
        if(
        $target_company['rif_letter'] === $cleanData['rif_letter'] && 
        $target_company['rif_number'] === $cleanData['rif_number'] && 
        intval($target_company['id']) !== $cleanData['id']
        )
            $error = 'El rif de la empresa está repetido';
    }    
}    

// Creating / updating the company
if($error === ''){    
    if($edit){
        $updated = $company_model->UpdateCompany($cleanData['id'], $cleanData);
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar la empresa';
    }
    else{
        $created = $company_model->CreateCompany($cleanData);
        if($created === false)
            $error = 'Hubo un error al intentar registrar la empresa';
    }
}

// Managing feedback message and binnacle
if($error === ''){
    if($edit){        
        $message = 'Empresa actualizada correctamente';

        $nameChanged = $cleanData['name'] !== $target_company['name'];
        $letterChanged = $cleanData['rif_letter'] !== $target_company['rif_letter'];
        $numberChanged = $cleanData['rif_number'] !== $target_company['rif_number'];
        $addressChanged = $cleanData['address'] !== $target_company['address'];

        $action = 'Actualizó la empresa ' . $target_company['name'];
        if($nameChanged)
            $action .= '. Al nombre ' . $cleanData['name'];

        if($letterChanged)
            $action .= '. A la letra de rif ' . $cleanData['rif_letter'];

        if($numberChanged)
            $action .= '. Al número de rif ' . $cleanData['rif_number'];

        if($addressChanged)
            $action .= '. Al la dirección ' . $cleanData['address'];
    }
    else{
        $message = 'Empresa registrada correctamente';
        $action = 'Creo la empresa ' . $cleanData['name'] . ' con el rif ' . $cleanData['rif_letter'] . '-' . $cleanData['rif_number'] . ' dirección: ' . $cleanData['address'];
    }
    $company_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){    
    if($edit)
        header("Location: $base_url/views/forms/company_form.php?message=$message&id=" . $cleanData['id']);
    else
        header("Location: $base_url/views/forms/company_form.php?message=$message&id=" . $created['id']);
}
else{
    if($edit){
        if($target_company === false)
            header("Location: $base_url/views/tables/search_company.php?error=$error");
        else
            header("Location: $base_url/views/forms/company_form.php?error=$error&id=" . $target_company['id']);
    }
    else
        header("Location: $base_url/views/forms/company_form.php?error=$error");
}

exit;
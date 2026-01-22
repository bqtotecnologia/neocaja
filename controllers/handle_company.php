<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';

include_once '../utils/Validator.php';

$error = '';
$edit = isset($_POST['id']);
$target_company = false;

if(empty($_POST)){
    $error = 'POST vacío';
}

if($error === '' && $edit){
    $id = Validator::ValidateRecievedId('id', 'POST');
    if(is_string($id))
        $error = $id;
}

if($error === ''){
    include_once '../models/company_model.php';
    $company_model = new CompanyModel();

    if($edit){
        $target_company = $company_model->GetCompany($id);
        if($target_company === false)
            $error = 'Empresa no encontrada';
    }
}

if($error === ''){
    include_once '../fields_config/companies.php';
    $cleanData = Validator::ValidatePOSTFields($companyFields);
    if(is_string($cleanData))
        $error = $cleanData;
}

if($error === ''){
    $exists = $company_model->GetCompanyByRif($cleanData['rif_letter'], $cleanData['rif_number']);
    if($exists !== false){
        if($edit){
            if(intval($exists['id']) !== intval($id))
                $error = 'El rif de la empresa está repetido';
        }
        else
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
            $action .= '. A la dirección ' . $cleanData['address'];
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
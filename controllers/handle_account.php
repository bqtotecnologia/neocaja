<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';

$error = '';
$target_company = false;

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
    include_once '../models/account_model.php';
    $account_model = new AccountModel();

    if($edit){
        $target_account = $account_model->GetAccount($id);
        if($target_account === false)
            $error = 'Cliente no encontrado';
    }
}

if($error === ''){
    include_once '../fields_config/accounts.php';
    $cleanData = Validator::ValidatePOSTFields($accountFields);
    if(is_string($cleanData))
        $error = $cleanData;
}

if($error === ''){
    $updateCompany = false;
    if($edit === false) $updateCompany = true;

    if($cleanData['company'] !== ''){
        include_once '../models/company_model.php';
        $company_model = new CompanyModel();

        $target_company = $company_model->GetCompany($cleanData['company']);
        if($target_company === false)
            $error = 'Empresa no encontrada';
    }
}

if($error === ''){
    if($cleanData['scholarship'] !== ''){
        include_once '../models/scholarship_model.php';
        $scholarship_model = new ScholarshipModel();

        
        $target_scholarship = $scholarship_model->GetScholarship($cleanData['scholarship']);
        if($target_scholarship === false)
            $error = 'Tipo de beca no encontrada';
    }
}

if($error === ''){
    $exists = $account_model->GetAccountByCedula($cleanData['cedula']);
    if($exists !== false){
        if($edit){
            if(intval($exists['id']) !== intval($id))
                $error = 'La cédula ingresada ya está repetida';    
        }
        else
            $error = 'La cédula ingresada ya está repetida';
    }
}

// Creating / updating the account
if($error === ''){    
    $cleanData['is_student'] = isset($_POST['is_student']) ? '1' : '0';
    $cleanData['scholarship'] = ($cleanData['scholarship'] === '' ? 'NULL' : $target_scholarship['id']);

    if($cleanData['scholarship'] === 'NULL')
        $cleanData['scholarship_coverage'] = 'NULL';
    else
        $cleanData['scholarship_coverage'] = ($cleanData['scholarship_coverage'] === '' ? 'NULL' : $cleanData['scholarship_coverage']);

    if($cleanData['scholarship_coverage'] === 'NULL')
        $cleanData['scholarship'] = 'NULL';    

    $cleanData['company'] = ($cleanData['company'] === '' ? 'NULL' : $target_company['id']);

    if($edit){
        $updated = $account_model->UpdateAccount($cleanData['id'], $cleanData);
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar el cliente';
        else{
            if(intval($target_account['company_id']) !== intval($cleanData['company']))
                $updateCompany = true;
        }
    }
    else{
        $created = $account_model->CreateAccount($cleanData);
        if($created === false)
            $error = 'Hubo un error al intentar registrar el cliente';
    }
}

if($error === '' && $updateCompany){
    if($edit)
        $account_id = $cleanData['id'];
    else
        $account_id = $created['id'];

    $company_history = $account_model->UpdateAccountCompany($account_id, $cleanData['company']);
    if($company_history === false)
        $error = 'Hubo un error al intentar crear el historial de empresa del estudiante';
}

// Managing feedback message and binnacle
if($error === ''){
    if($edit){        
        $message = 'Cliente actualizado correctamente';

        $cedulaChanged = $cleanData['changed'] !== $target_account['changed'];
        $namesChanged = $cleanData['names'] !== $target_account['names'];
        $surnamesChanged = $cleanData['surnames'] !== $target_account['surnames'];
        $addressChanged = $cleanData['address'] !== $target_account['address'];
        $is_studentChanged = intval($cleanData['is_student']) !== intval($target_account['is_student']);
        $scholarshipChanged = intval($cleanData['scholarship']) !== intval($target_account['scholarship_id']);
        $scholarshipCoverageChanged = intval($cleanData['scholarship_coverage']) !== intval($target_account['scholarship_coverage']);
        $companyChanged = intval($cleanData['company']) !== intval($target_account['company_id']);

        $action = 'Actualizó el cliente ' . $target_account['names'] . ' ' . $target_account['surnames'];

        if($cedulaChanged)
            $action .= '. A la cédula ' . $cleanData['cedula'];

        if($namesChanged)
            $action .= '. Al nombre ' . $cleanData['names'];

        if($surnamesChanged)
            $action .= '. Al apellido ' . $cleanData['surnames'];

        if($addressChanged)
            $action .= '. A la dirección ' . $cleanData['address'];

        if($is_studentChanged){
            if($cleanData['is_student'] === '1')
                $action .= '. Ahora es un estudiante';
            else
                $action .= '. Ya no es un estudiante';
        }

        if($scholarshipChanged)
            $action .= '. A la beca ' . $target_scholarship['name'];

        if($scholarshipCoverageChanged)
            $action .= '. Al porcentaje de beca ' . $cleanData['scholarship_coverage'] . '%';

        if($companyChanged)
            $action .= '. A la empresa ' . $target_company['name'];

    }
    else{
        $message = 'Cliente registrado correctamente';
        $action = 'Creo el cliente ' . $cleanData['names'] . ' ' . $cleanData['surnames'] . ' con la cédula ' . $cleanData['cedula'];
    }
    $account_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($error === ''){    
    if($edit)
        header("Location: $base_url/views/forms/account_form.php?message=$message&id=" . $cleanData['id']);
    else
        header("Location: $base_url/views/forms/account_form.php?message=$message&id=" . $created['id']);
}
else{
    if($edit){
        if($target_account === false)
            header("Location: $base_url/views/tables/search_account.php?error=$error");
        else
            header("Location: $base_url/views/forms/account_form.php?error=$error&id=" . $target_account['id']);
    }
    else
        header("Location: $base_url/views/forms/account_form.php?error=$error");
}

exit;
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
    'cedula' => [
        'min' => 7,
        'max' => 10,
        'required' => true,
        'type' => 'integer',
        'suspicious' => true,
    ],
    'names' => [
        'min' => 1,
        'max' => 100,
        'required' => true,
        'type' => 'string',
        'suspicious' => true,
    ],
    'surnames' => [
        'min' => 1,
        'max' => 100,
        'required' => true,
        'type' => 'string',
        'suspicious' => true,
    ],
    'address' => [
        'min' => 10,
        'max' => 255,
        'required' => true,
        'type' => 'string',
        'suspicious' => false,
    ],
    'address' => [
        'min' => 1,
        'max' => 1000,
        'required' => true,
        'type' => 'string',
        'suspicious' => false,
    ],
    'scholarship' => [
        'min' => 0,
        'max' => 11,
        'required' => false,
        'type' => 'integer',
        'suspicious' => true,
    ],
    'phone' => [
        'min' => 11,
        'max' => 11,
        'required' => true,
        'type' => 'string',
        'suspicious' => true,
    ],
    'scholarship_coverage' => [
        'min' => 1,
        'max' => 11,
        'required' => true,
        'type' => 'integer',
        'suspicious' => true,
    ],
    'company' => [
        'min' => 0,
        'max' => 11,
        'required' => false,
        'type' => 'integer',
        'suspicious' => true,
    ],
];

$result = Validator::ValidatePOSTFields($fields_config);
if(is_string($result))
    $error = $result;
else
    $cleanData = $result;

$edit = isset($_POST['id']);
$updateCompany = false;
if($edit === false) $updateCompany = true;

if($error === ''){
    include_once '../models/account_model.php';
    $account_model = new AccountModel();

    if($edit){
        $target_account = $account_model->GetAccount($cleanData['id']);
        if($target_account === false)
            $error = 'Cliente no encontrado';
    }
    else{
        $target_account = $account_model->GetAccountByCedula($cleanData['cedula']);
        if($target_account !== false)
            $error = 'La cédula del cliente está repetida';
    }  
}

if($error === ''){
    if($cleanData['company'] !== 0){
        include_once '../models/company_model.php';
        $company_model = new CompanyModel();
    
        $target_company = $company_model->GetCompany($cleanData['company']);
        if($target_company === false)
            $error = 'Empresa no encontrada';
    }
}

if($error === ''){
    if($cleanData['scholarship'] !== 0){
        include_once '../models/scholarship_model.php';
        $scholarship_model = new ScholarshipModel();
    
        $target_scholarship = $scholarship_model->GetScholarship($cleanData['scholarship']);
        if($target_scholarship === false)
            $error = 'Tipo de beca no encontrada';
    }
}

if($error === ''){
    if($edit){
        if($target_account['id'] !== $cleanData['id'] && $target_account['cedula'] === $cleanData['cedula'])
            $error = 'La cédula ingresada ya está repetida';
    }
}

// Creating / updating the account
if($error === ''){    
    $cleanData['is_student'] = isset($_POST['is_student']) ? '1' : '0';
    $cleanData['scholarship'] = ($cleanData['scholarship'] === 0 ? 'NULL' : $target_scholarship['id']);

    if($cleanData['scholarship'] === 'NULL')
        $cleanData['scholarship_coverage'] = 'NULL';
    else
        $cleanData['scholarship_coverage'] = ($cleanData['scholarship_coverage'] === 0 ? 'NULL' : $cleanData['scholarship_coverage']);

    if($cleanData['scholarship_coverage'] === 'NULL')
        $cleanData['scholarship'] = 'NULL';

    

    if($cleanData['scholarship'] === 'NULL')
        $cleanData['scholarship_coverage'] = 'NULL';

    $cleanData['company'] = ($cleanData['company'] === 0 ? 'NULL' : $target_company['id']);

    if($cleanData['scholarship'] === 0)
        $cleanData['scholarship_coverage'] = 0;

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
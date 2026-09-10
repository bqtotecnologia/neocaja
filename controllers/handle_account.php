<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../utils/validate_user_type.php';
include_once '../utils/Validator.php';

$error = '';
$target_company = false;
$delete = false;

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
            //$error = 'La cédula ingresada ya está repetida';
            $error = '';
    }
}

if($error === ''){
    $cleanData['is_student'] = isset($_POST['is_student']) ? '1' : '0';
    if($cleanData['is_student'] === '0' && ($cleanData['scholarship'] !== '' || $cleanData['scholarship_coverage'] !== ''))
        $error = 'Solo los estudiantes del iujo pueden tener beca.';
}

// Creating / updating the account
if($error === ''){        
    $updateHistory = false;

    if($edit){
        $updated = $account_model->UpdateAccount($cleanData['id'], $cleanData);
        if($updated === false)
            $error = 'Hubo un error al intentar actualizar el cliente';
        else{
            if(
                intval($target_account['company_id']) !== intval($cleanData['company']) || // Hubo un cambio de empresa
                intval($target_account['scholarship_id']) !== intval($cleanData['scholarship']) || // Hubo un cambio de beca
                intval($target_account['scholarship_coverage']) !== intval($cleanData['scholarship_coverage']) // Hubo un cambio en el porcentaje de cobertura
            )
                $updateHistory = true;
        }
    }
    else{
        $updateHistory = true;
        $created = $account_model->CreateAccount($cleanData);
        if($created === false)
            $error = 'Hubo un error al intentar registrar el cliente';
        else
            $target_account = $created;
    }
}

// Managing scholarship history
if($error === '' && $updateHistory){    
    $data = [
        'account' => $target_account['id'],
        'company' => ($cleanData['company'] === '' ? NULL : $cleanData['company']),
        'scholarship' => ($cleanData['scholarship'] === '' ? NULL : $cleanData['scholarship']),
        'scholarship_coverage' => $cleanData['scholarship_coverage']
    ];

    if($data['scholarship'] === null || intval($data['scholarship_coverage']) === 0){
        $data['scholarship'] = null;
        $data['scholarship_coverage'] = null;
    }

    $updated = $account_model->UpdateAccountHistory($target_account['id'], $data);
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
        $companyChanged = intval($target_account['company_id']) !== intval($cleanData['company']);
        $scholarshipChanged = (intval($target_account['scholarship_id']) !== intval($cleanData['scholarship']) || intval($target_account['scholarship_coverage']) !== intval($cleanData['scholarship_coverage']));
        
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
            $action .= '. A la beca ' . $target_scholarship['name'] . ' ' . $cleanData['scholarship_coverage'] . '%';

        if($companyChanged)
            $action .= '. A la empresa ' . $target_company['name'];

    }
    else{
        $message = 'Cliente registrado correctamente';
        $action = 'Creo el cliente ' . $cleanData['names'] . ' ' . $cleanData['surnames'] . ' con la cédula ' . $cleanData['cedula'];
    }
    $account_model->CreateBinnacle($_SESSION['neocaja_id'], $action);
}

if($delete && $edit === false){
    // Lo borramos solo si hubo un error durante la creación
    $account_model->DeleteAccount($cleanData['cedula']);
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
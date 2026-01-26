<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';


$edit = isset($_GET['id']);
if($edit){
    include_once '../../utils/Validator.php';
    $id = Validator::ValidateRecievedId();

    if(is_string($id)){
        header("Location: $base_url/views/tables/search_account.php?error=$id");
        exit;
    }   

    include_once '../../models/account_model.php';
    $account_model = new AccountModel();
    $target_account = $account_model->GetAccount($id);
    if($target_account === false){
        header("Location: $base_url/views/tables/search_account.php?error=Cliente no encontrado");
        exit;
    }
}

include_once '../../models/scholarship_model.php';
include_once '../../models/company_model.php';

$scholarship_model = new ScholarshipModel();
$company_model = new CompanyModel();

$scholarships = $scholarship_model->GetScholarships();
$companies = $company_model->GetCompanies();

$display_scholarships = [];
$display_companies = [];

foreach($scholarships as $scholarship){
    array_push($display_scholarships,
    [
        'display' => $scholarship['name'],
        'value' => $scholarship['id']
    ]);
}

foreach($companies as $company){
    array_push($display_companies,
    [
        'display' => $company['name'],
        'value' => $company['id']
    ]);
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';
include_once '../../fields_config/accounts.php';


$formBuilder = new FormBuilder(
    '../../controllers/handle_account.php',
    'POST',
    ($edit ? 'Editar' : 'Registrar nuevo') . ' cliente',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $accountFields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_account.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>
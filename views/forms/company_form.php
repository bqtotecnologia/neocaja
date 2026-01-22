<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';

$edit = isset($_GET['id']);
$form = true;
if($edit){
    include_once '../../utils/Validator.php';
    $id = Validator::ValidateRecievedId();

    if(is_string($id)){
        header("Location: $base_url/views/tables/search_company.php?error=$id");
        exit;
    }   

    include_once '../../models/company_model.php';
    $company_model = new CompanyModel();
    $target_company = $company_model->GetCompany($id);
    if($target_company === false){
        header("Location: $base_url/views/tables/search_company.php?error=Empresa no encontrada");
        exit;
    }
}

$rif_letters = ['V', 'J', 'E', 'P', 'G'];
$display_rif_letters = [];
foreach($rif_letters as $letter){
    array_push($display_rif_letters,
    [
        'display' => $letter,
        'value' => $letter
    ]);
}

include_once '../common/header.php';
include_once '../../fields_config/companies.php';
include_once '../../utils/FormBuilder.php';

$formBuilder = new FormBuilder(
    '../../controllers/handle_company.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nueva') . ' empresa',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $companyFields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_company.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>
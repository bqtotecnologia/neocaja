<?php
$admitted_user_types = ['Super', 'Cajero'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

$edit = isset($_GET['id']);
$form = true;
if($edit){
    if(!is_numeric($_GET['id'])){
        header("Location: $base_url/views/tables/search_scholarship.php?error=Id inválido");
        exit;
    }   

    include_once '../../models/scholarship_model.php';
    $scholarship_model = new ScholarshipModel();
    $target_scholarship = $scholarship_model->GetScholarship($_GET['id']);
    if($target_scholarship === false){
        header("Location: $base_url/views/tables/search_scholarship.php?error=Beca no encontrada");
        exit;
    }
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';
include_once '../../fields_config/scholarships.php';


$formBuilder = new FormBuilder(
    '../../controllers/handle_scholarship.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nueva') . ' beca',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $scholarshipFields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_scholarship.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>
<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';


$edit = isset($_GET['id']);
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
include_once '../../utils/FormBuilder.php';

$fields = [
    [
        'name' => 'name',
        'display' => 'Nombre',
        'placeholder' => 'Nombre de la compañía',
        'id' => 'name',
        'type' => 'text',
        'size' => 8,
        'max' => 255,
        'min' => 1,
        'required' => true,
        'value' => $edit ? $target_company['name'] : ''
    ],
    [
        'name' => 'rif_letter',
        'display' => 'Letra del rif',
        'placeholder' => '',
        'id' => 'rif_letter',
        'type' => 'select',
        'min' => 1,
        'max' => 1,
        'size' => 3,
        'required' => true,
        'value' => $edit ? $target_company['rif_letter'] : 'V',
        'elements' => $display_rif_letters
    ],
    [
        'name' => 'rif_number',
        'display' => 'Rif',
        'placeholder' => '9 dígitos',
        'id' => 'rif_number',
        'type' => 'text',
        'min' => 9,
        'max' => 9,
        'size' => 5,
        'required' => false,
        'value' => $edit ? $target_company['rif_number'] : '',
    ],
    [
        'name' => 'address',
        'display' => 'Dirección',
        'placeholder' => 'Dirección de la empresa',
        'id' => 'address',
        'type' => 'textarea',
        'size' => 8,
        'required' => false,
        'value' => $edit ? $target_company['address'] : '',
    ],
];

if($edit){
    $id_field = [
        'name' => 'id',
        'value' => $target_company['id']
    ];
    array_push($fields, $id_field);
}

$formBuilder = new FormBuilder(
    '../../controllers/handle_company.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nueva') . ' empresa',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $fields
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
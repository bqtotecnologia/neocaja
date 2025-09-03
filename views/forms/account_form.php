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

$fields = [
    [
        'name' => 'cedula',
        'display' => 'Cédula',
        'placeholder' => '',
        'id' => 'cedula',
        'type' => 'integer',
        'size' => 5,
        'max' => 10,
        'min' => 7,
        'required' => true,
        'value' => $edit ? $target_account['cedula'] : ''
    ],
    [
        'name' => 'names',
        'display' => 'Nombres',
        'placeholder' => 'Ambos nombres',
        'id' => 'names',
        'type' => 'text',
        'size' => 8,
        'min' => 5,
        'max' => 100,
        'required' => true,
        'value' => $edit ? $target_account['names'] : '',
    ],
    [
        'name' => 'surnames',
        'display' => 'Apellidos',
        'placeholder' => 'Ambos apellidos',
        'id' => 'surnames',
        'type' => 'text',
        'size' => 8,
        'min' => 5,
        'max' => 100,
        'required' => true,
        'value' => $edit ? $target_account['surnames'] : '',
    ],
    [
        'name' => 'phone',
        'display' => 'Teléfono',
        'placeholder' => 'Teléfono',
        'id' => 'phone',
        'type' => 'text',
        'size' => 8,
        'min' => 11,
        'max' => 11,
        'required' => true,
        'value' => $edit ? $target_account['phone'] : '',
    ],
    [
        'name' => 'address',
        'display' => 'Dirección',
        'placeholder' => 'Dirección del cliente',
        'id' => 'address',
        'type' => 'textarea',
        'size' => 8,
        'required' => true,
        'value' => $edit ? $target_account['address'] : '',
    ],
    [
        'name' => 'is_student',
        'display' => 'Es estudiante',
        'placeholder' => '',
        'id' => 'is_student',
        'type' => 'checkbox',
        'size' => 5,
        'required' => false,
        'value' => $edit ? [$target_account['is_student']] : ['0'],
        'elements' => [['display' => 'Es estudiante', 'value' => '1']],
    ],
    [
        'name' => 'scholarship',
        'display' => 'Beca',
        'placeholder' => '',
        'id' => 'scholarship',
        'type' => 'select',
        'size' => 6,
        'required' => false,
        'value' => $edit ? $target_account['scholarship_id'] : '',
        'elements' => $display_scholarships,
    ],
    [
        'name' => 'scholarship_coverage',
        'display' => '% Cobertura de la beca',
        'placeholder' => 'Ejemplo, 30, 50, 100',
        'id' => 'scholarship_coverage',
        'type' => 'integer',
        'size' => 6,
        'required' => false,
        'value' => $edit ? ($target_account['scholarship_coverage'] === NULL ? '0' : $target_account['scholarship_coverage'] ) : '0',
    ],
    [
        'name' => 'company',
        'display' => 'Empresa',
        'placeholder' => '',
        'id' => 'company',
        'type' => 'select',
        'size' => 8,
        'required' => false,
        'value' => $edit ? $target_account['company_id'] : '',
        'elements' => $display_companies
    ],
];

if($edit){
    $id_field = [
        'name' => 'id',
        'value' => $target_account['id']
    ];
    array_push($fields, $id_field);
}

$formBuilder = new FormBuilder(
    '../../controllers/handle_account.php',
    'POST',
    ($edit ? 'Editar' : 'Registrar nuevo') . ' cliente',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $fields
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
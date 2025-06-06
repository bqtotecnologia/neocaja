<?php
$admitted_user_types = ['Super', 'Cajero'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';



$edit = isset($_GET['id']);
if($edit){
    if(!is_numeric($_GET['id'])){
        header("Location: $base_url/views/tables/search_bank.php?error=Id inválido");
        exit;
    }   

    include_once '../../models/bank_model.php';
    $bank_model = new BankModel();
    $target_bank = $bank_model->GetBankById($_GET['id']);
    if($target_bank === false){
        header("Location: $base_url/views/tables/search_bank.php?error=Banco no encontrado");
        exit;
    }
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';

$fields = [
    [
        'name' => 'name',
        'display' => 'Nombre',
        'placeholder' => 'Nombre del banco',
        'id' => 'name',
        'type' => 'text',
        'size' => 8,
        'max' => 255,
        'min' => 5,
        'required' => true,
        'value' => $edit ? $target_bank['name'] : ''
    ],
    [
        'name' => 'code',
        'display' => 'Codigo',
        'placeholder' => 'Código del banco',
        'id' => 'name',
        'type' => 'text',
        'size' => 8,
        'max' => 4,
        'min' => 4,
        'required' => true,
        'value' => $edit ? $target_bank['code'] : ''
    ],
];

if($edit){
    $id_field = [
        'name' => 'id',
        'value' => $target_bank['id']
    ];
    array_push($fields, $id_field);
}

$formBuilder = new FormBuilder(
    '../../controllers/handle_bank.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nuevo') . ' banco',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $fields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_bank.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>
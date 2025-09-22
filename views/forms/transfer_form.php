<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';


$edit = isset($_GET['id']);
if($edit){
    include_once '../../utils/Validator.php';
    $id = Validator::ValidateRecievedId();

    if(is_string($id)){
        header("Location: $base_url/views/tables/search_transfers.php?error=$id");
        exit;
    }   

    include_once '../../models/transfers_model.php';
    $transfers_model = new TransfersModel();
    $target_transfer = $transfers_model->GetTransfer($id);
    if($transfers_model === false){
        header("Location: $base_url/views/tables/search_transfers.php?error=Transferencia no encontrada");
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
        'name' => 'account_number',
        'display' => 'Número de cuenta',
        'placeholder' => 'Número de cuenta',
        'id' => 'phone',
        'type' => 'text',
        'size' => 8,
        'max' => 20,
        'min' => 20,
        'required' => true,
        'value' => $edit ? $target_transfer['account_number'] : ''
    ],
    [
        'name' => 'document_letter',
        'display' => 'Letra de documento',
        'placeholder' => '',
        'id' => 'document_letter',
        'type' => 'select',
        'min' => 1,
        'max' => 1,
        'size' => 3,
        'required' => true,
        'value' => $edit ? $target_transfer['document_letter'] : 'V',
        'elements' => $display_rif_letters
    ],
    [
        'name' => 'document_number',
        'display' => 'Documento',
        'placeholder' => 'Número de Rif/Cédula',
        'id' => 'document-number',
        'type' => 'text',
        'min' => 7,
        'max' => 45,
        'size' => 5,
        'required' => true,
        'value' => $edit ? $target_transfer['document_number'] : '',
    ],
    [
        'name' => 'active',
        'display' => 'Activo',
        'placeholder' => '',
        'id' => 'active',
        'type' => 'checkbox',
        'size' => 4,
        'required' => false,
        'value' => $edit ? [$target_transfer['active']] : ['1'],
        'elements' => [
            [
                'display' => 'Activo',
                'value' => '1'
            ]
        ]
    ],
];

if($edit){
    $id_field = [
        'name' => 'id',
        'value' => $target_transfer['id']
    ];
    array_push($fields, $id_field);
}

$formBuilder = new FormBuilder(
    '../../controllers/handle_transfer.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nueva') . ' cuenta bancaria',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $fields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_transfers.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>
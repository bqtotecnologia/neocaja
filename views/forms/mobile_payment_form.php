<?php
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';


$edit = isset($_GET['id']);
if($edit){
    include_once '../../utils/Validator.php';
    $id = Validator::ValidateRecievedId();

    if(is_string($id)){
        header("Location: $base_url/views/tables/search_mobile_payments.php?error=$id");
        exit;
    }   

    include_once '../../models/mobile_payments_model.php';
    $mobile_payment_model = new MobilePaymentsModel();
    $target_mobile_payment = $mobile_payment_model->GetMobilePayment($id);
    if($mobile_payment_model === false){
        header("Location: $base_url/views/tables/search_mobile_payments.php?error=Pago móvil no encontrado");
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

include_once '../../utils/banks.php';
$display_banks = [];
foreach($banks as $bank){
    array_push($display_banks,
    [
        'display' => $bank,
        'value' => $bank
    ]);
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';

$fields = [
    [
        'name' => 'phone',
        'display' => 'Teléfono',
        'placeholder' => '04160000000',
        'id' => 'phone',
        'type' => 'text',
        'size' => 8,
        'max' => 11,
        'min' => 11,
        'required' => true,
        'value' => $edit ? $target_mobile_payment['phone'] : ''
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
        'value' => $edit ? $target_mobile_payment['document_letter'] : 'V',
        'elements' => $display_rif_letters
    ],
    [
        'name' => 'document_number',
        'display' => 'Documento',
        'placeholder' => 'Número de Rif/Cédula',
        'id' => 'document_number',
        'type' => 'text',
        'min' => 7,
        'max' => 45,
        'size' => 5,
        'required' => true,
        'value' => $edit ? $target_mobile_payment['document_number'] : '',
    ],
    [
        'name' => 'bank',
        'display' => 'Banco',
        'placeholder' => '',
        'id' => 'bank',
        'type' => 'select',
        'min' => 5,
        'max' => 60,
        'size' => 6,
        'required' => true,
        'value' => $edit ? $target_mobile_payment['bank'] : '',
        'elements' => $display_banks
    ],
    [
        'name' => 'active',
        'display' => 'Activo',
        'placeholder' => '',
        'id' => 'active',
        'type' => 'checkbox',
        'size' => 4,
        'required' => false,
        'value' => $edit ? [$target_mobile_payment['active']] : ['1'],
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
        'value' => $target_mobile_payment['id']
    ];
    array_push($fields, $id_field);
}

$formBuilder = new FormBuilder(
    '../../controllers/handle_mobile_payment.php',    
    'POST',
    ($edit ? 'Editar' : 'Registrar nuevo') . ' pago móvil',
    ($edit ? 'Editar' : 'Registrar'),
    '',
    $fields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_mobile_payments.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>
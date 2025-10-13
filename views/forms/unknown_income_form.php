<?php
$admitted_user_types = ['Tecnología', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

include_once '../../utils/Validator.php';

$id = Validator::ValidateRecievedId();
if(is_string($id)){
    header("Location: $base_url/views/tables/search_unknown_incomes_by_date.php?error=" . $id);
    exit;
}

include_once '../../models/unknown_incomes_model.php';    
$unknown_model = new UnknownIncomesModel();

$target_income = $unknown_model->GetUnknownIncome($id);
if($target_income === false){
    header("Location: $base_url/views/tables/search_unknown_incomes_by_date.php?error=Ingreso no identificado no encontrado");
    exit;
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';


include_once '../../models/account_model.php';    
$account_model = new AccountModel();
$accounts = $account_model->GetAccounts();

$display_accounts = [];

foreach($accounts as $account){
    $to_add = [
        'display' => $account['surnames'] . ' ' . $account['names'] .' (' . $account['cedula'] . ')', 
        'value' => $account['id'],
    ];

    array_push($display_accounts, $to_add);
}


$fields = [
    [
        'name' => 'price',
        'display' => 'Monto',
        'placeholder' => '',
        'id' => 'price',
        'type' => 'decimal',
        'size' => 4,
        'max' => 11,
        'min' => 1,
        'required' => false,
        'value' => $target_income['price']
    ],
    [
        'name' => 'date',
        'display' => 'Fecha',
        'placeholder' => '',
        'id' => 'date',
        'type' => 'date',
        'size' => 4,
        'required' => false,
        'value' => $target_income['date'],
    ],
    [
        'name' => 'ref',
        'display' => 'Referencia',
        'placeholder' => '',
        'id' => 'ref',
        'type' => 'text',
        'size' => 4,
        'max' => 100,
        'min' => 1,
        'required' => false,
        'value' => $target_income['ref'],
    ],
    [
        'name' => 'description',
        'display' => 'Descripción',
        'placeholder' => '',
        'id' => 'description',
        'type' => 'textarea',
        'size' => 10,
        'max' => 100,
        'min' => 1,
        'required' => false,
        'value' => $target_income['description'],
    ],
    [
        'name' => 'account',
        'display' => 'Cliente relacionado',
        'placeholder' => '',
        'id' => 'account',
        'type' => 'select',
        'size' => 8,
        'max' => 11,
        'min' => 1,
        'required' => false,
        'value' => $target_income['account_id'],
        'elements' => $display_accounts
    ],
    [
        'name' => 'id',
        'value' => $target_income['id']
    ]
];

$formBuilder = new FormBuilder(
    '../../controllers/update_unknown_income.php',    
    'POST',
    'Detalles de un ingreso no identificado',
    'Actualizar',
    '',
    $fields
);

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_unknown_incomes_by_date.php'; include_once '../layouts/backButton.php'; ?>
    </div>

    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>

<script>
    const ids = [
        'date',
        'price',
        'description',
        'ref'
    ]

    ids.forEach((id) => {
        document.getElementById(id).disabled = true
    })

</script>
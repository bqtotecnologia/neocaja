<?php
$admitted_user_types = ['Tecnologia', 'Super', 'SENIAT'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/Validator.php';

$error = '';
$form = true;
$id = Validator::ValidateRecievedId();
if(is_string($id))
    $error = $id;

if($error === ''){
    include_once '../../models/unknown_incomes_model.php';    
    $unknown_model = new UnknownIncomesModel();    
    $target_income = $unknown_model->GetUnknownIncome($id);
    if($target_income === false)
        $error = 'Ingreso no identificado no encontrado';
}

if($error !== ''){
    header("Location: $base_url/views/tables/search_unknown_incomes_by_date.php?error=$error");
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

$title = 'Detalles de un ingreso ';

if($target_income['account_id'] === null)
    $title .= 'no ';

$title .= 'identificado';

include_once '../../fields_config/unknown_incomes.php';
$formBuilder = new FormBuilder(
    '../../controllers/update_unknown_income.php',    
    'POST',
    $title,
    'Actualizar',
    '',
    $unknownIncomeFields
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

<?php if($_SESSION['neocaja_rol'] === 'SENIAT') { ?>
    <script>
        const submitButton = document.querySelector('button[type="submit"]');
        submitButton.remove()

        const accountSelect = document.getElementById('account')
        accountSelect.disabled = true
    </script>
<?php } ?>

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
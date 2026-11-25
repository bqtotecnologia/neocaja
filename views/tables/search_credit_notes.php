<?php
$admitted_user_types = ['Super', 'Cajero', 'SENIAT', 'Administrador'];
include_once '../../utils/validate_user_type.php';

if(!empty($_POST)){
    if(strtotime($_POST['start_date']) > strtotime($_POST['end_date'])){
        $error = 'La fecha final debe ser posterior o igual a la fecha inicial';
        header("Location: $base_url/views/tables/search_credit_notes.php?error=$error");
        exit;
    }
}

include_once '../common/header.php';
include_once '../../utils/FormBuilder.php';
include_once '../../fields_config/date_range.php';


$formBuilder = new FormBuilder(
    '',    
    'POST',
    'Buscar notas de crédito',
    'Buscar',
    '',
    $dateRangeFields,
    false
);

include_once '../../models/invoice_model.php';
$invoice_model = new InvoiceModel();

if(!empty($_POST))
    $credit_notes = $invoice_model->GetCreditNotesBetweenDates($_POST['start_date'], $_POST['end_date']);
else
    $credit_notes = $invoice_model->GetCreditNotes();

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../panel.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <?php $formBuilder->DrawForm(); ?>
    </div>

        <div class="col-12 row justify-content-center px-4">
            <div class="col-12 row justify-content-center x_panel">
                <div class="table-responsive">
                    <?php include '../common/tables/credit_note_table.php'; ?>
                </div>
            </div>
        </div>
</div>

<?php include_once '../common/footer.php'; ?>
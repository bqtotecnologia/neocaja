<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';
include_once '../../utils/Validator.php';

$id = Validator::ValidateRecievedId();
$error = '';

if(is_string($id)){
    $error = $id;    
}

include_once '../../models/invoice_model.php';
$invoice_model = new InvoiceModel();

$target_invoice = $invoice_model->GetInvoice($id);
if($target_invoice === false){
    $error = 'Factura no encontrada';
}

if($error !== ''){
    header('Location: ' . $base_url . '/views/tables/search_invoices_of_today.php?error='. $error);
    exit;
}

$payment_methods = $invoice_model->GetPaymentMethodsOfInvoice($id);
var_dump($payment_methods);
$concepts = $invoice_model->GetConceptsOfInvoice($id);

include '../../views/common/header.php';




$invoices = $invoice_model->GetAllInvoices();
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Lista de todas las facturas</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="col-12 row justify-content-center x_panel">
            <?php 
            $btn_url = '../forms/invoice_form.php'; 
            include_once '../layouts/addButton.php';
            include '../common/tables/invoice_table.php';
            ?>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>
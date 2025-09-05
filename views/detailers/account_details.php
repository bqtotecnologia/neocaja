<?php 
$admitted_user_types = ['Cajero', 'Super'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';
include_once '../../utils/Validator.php';
include_once '../../utils/months_data.php';

$id = Validator::ValidateRecievedId();
$error = '';

if(is_string($id)){
    $error = $id;    
}

include_once '../../models/invoice_model.php';
include_once '../../models/account_model.php';
include_once '../../models/siacad_model.php';

$invoice_model = new InvoiceModel();
$account_model = new AccountModel();
$siacad = new SiacadModel();

$target_account = $account_model->GetAccount($id);
if($target_account === false){
    $error = 'Cliente no encontrado';
}

if($error !== ''){
    header('Location: ' . $base_url . '/views/tables/search_account.php?error='. $error);
    exit;
}

$invoices = $invoice_model->GetInvoicesOfAccount($target_account['id']);
$target_student = $siacad->GetEstudianteByCedula($target_account['cedula']);

include '../../views/common/header.php';

?>

<div class="row justify-content-center">
    
    <div class="col-12 row justify-content-center">
        <?php $btn_url = $base_url . '/views/tables/search_account.php'; include_once '../layouts/backButton.php'; ?>
    </div>

    <div class="col-12 text-center mt-4">
        <h1 class="h1 text-black">Datos de cliente </h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <section class="col-12 row justify-content-center h6 bg-white py-2" style="border: 1px solid #d6d6d6ff !important">
            <div class="row col-6 justify-content-center align-self-start">
                <div class="row col-10 justify-content-start align-items-start">
                    <label class="fw-bold mx-2">
                        Nombre completo:
                    </label>
                    <span class="">
                        <?= $target_account['names'] . ' ' . $target_account['surnames'] ?>
                    </span>
                </div>

                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Cédula:
                    </label>
                    <span class="">
                        <?= $target_account['cedula'] ?>
                    </span>
                </div>

                <?php if($target_account['company'] !== null) { ?>
                    <div class="row col-10 justify-content-start align-items-middle">
                        <label class="fw-bold mx-2">
                            Empresa:
                        </label>
                        <span class="">
                            <?= $target_account['company'] ?>
                        </span>
                    </div> 
                    
                    <div class="row col-10 justify-content-start align-items-middle">
                        <label class="fw-bold mx-2">
                            RIF:
                        </label>
                    <span class="">
                            <?= $target_account['rif_letter'] . $target_account['rif_number'] ?>
                        </span>
                    </div> 
                <?php } ?>

                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Dirección:
                    </label>
                    <span class="">
                        <?= $target_account['address'] ?>
                    </span>
                </div>

                
            </div>

            <div class="row col-6 justify-content-center align-self-start">
                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Beca:
                    </label>
                    <span class="">
                        <?= $target_account['scholarship'] . ' ' . intval($target_account['scholarship_coverage']) . '%' ?>
                    </span>
                </div>

                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Teléfono:
                    </label>
                    <span class="">
                        <?= $target_account['phone'] ?>
                    </span>
                </div>
                
                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Estudiante del IUJO:
                    </label>
                    <span class="">
                        <?php if($target_student === false) { ?>
                            <i class="fa fa-cancel text-danger"></i>
                        <?php } else { ?>
                            <i class="fa fa-check text-success"></i>
                        <?php } ?>
                    </span>
                </div>

                <?php if($target_student !== false) { ?>
                    <div class="row col-10 justify-content-start align-items-middle">
                        <label class="fw-bold mx-2">
                            Carrera:
                        </label>
                        <span class="">
                            <?= $target_student['carrera'] ?>
                        </span>
                    </div>
                <?php } ?>

                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Fecha de registro:
                    </label>
                    <span class="">
                        <?= date('d/m/Y', strtotime($target_account['created_at'])) ?>
                    </span>
                </div>
            </div>
        </section>        

        <section class="col-12 row justify-content-center h6 bg-white py-2" style="border: 1px solid #d6d6d6ff !important">
            <div class="row col-12 text-center">
                <h2 class="w-100 text-center h2">Facturas registradas</h2>
            </div>

            <div class="row col-12 justify-content-center">
                <div class="table-responsive">
                    <?php include_once '../common/tables/invoice_table.php'; ?>
                </div>
            </div>
        </section>
    </div>

</div>

<?php include '../common/footer.php'; ?>
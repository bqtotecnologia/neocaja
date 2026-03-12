<?php 
$admitted_user_types = ['Cajero', 'Super', 'Estudiante', 'SENIAT'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/Validator.php';
include_once '../../utils/prettyCiphers.php';

$id = Validator::ValidateRecievedId();
$error = '';

if(is_string($id)){
    $error = $id;    
}

include_once '../../models/remote_payments_model.php';
include_once '../../models/account_model.php';
include_once '../../models/product_model.php';
include_once '../../models/coin_model.php';
include_once '../../models/unknown_incomes_model.php';

$payment_model = new RemotePaymentsModel();
$account_model = new AccountModel();
$siacad = new ProductModel();
$coin_model = new CoinModel();
$unknown_model = new UnknownIncomesModel();

$target_payment = $payment_model->GetAccountPayment($id);
if($target_payment === false){
    $error = 'Pago no encontrado';
}

if($_SESSION['neocaja_rol'] === 'Estudiante'){
    if($target_payment['cedula'] !== $_SESSION['neocaja_cedula']){
        $error = 'Pago remoto inválido';
    }
}

if($error !== ''){
    if($_SESSION['neocaja_rol'] !== 'Estudiante')
        header('Location: ' . $base_url . '/views/tables/my_payments.php?error='. $error);
    else
        header('Location: ' . $base_url . '/views/panel.php?error='. $error);
    exit;
}

$target_account = $account_model->GetAccount($target_payment['account_id']);
$payment_method = $payment_model->GetPaymentMethodOfPayment($target_payment);
$products = $payment_model->GetProductsOfPayment($id);
$coinValues = $coin_model->GetCoinValuesOfDate($target_payment['date']);
$coincidences = $unknown_model->GetUnknownIncomesByDateAndReference($target_payment['date'], $target_payment['ref']);
$payment_identified = false;
include '../../views/common/header.php';

?>

<div class="row justify-content-center">
    
    <div class="col-12 row justify-content-center">
        <?php 
        if($_SESSION['neocaja_rol'] === 'Estudiante')
            $btn_url = $base_url . '/views/tables/my_payments.php'; 
        else
            $btn_url = $base_url . '/views/tables/search_remote_payments.php?state=' . $target_payment['state']; 
        include_once '../layouts/backButton.php'; 
        ?>
    </div>

    <div class="col-12 text-center mt-4">
        <h1 class="h1 text-black">Pago remoto</h1>
    </div>    


    <div class="row col-12 m-0 p-0 justify-content-center h5 mb-3">
        <section class="x_panel d-flex row mb-3 col-12 justify-content-center text-center">
            <div class="col-12 col-lg-4 p-2">
                <label class="col-12 fw-bold align-middle m-0">Cliente</label>
                <label class="cursor-pointer align-middle" onclick="CopyToClipboard(this)" title="Click para copiar en el portapapeles">
                    <?= $target_account['names'] . ' ' . $target_account['surnames'] ?>
                </label>
            </div>
            <div class="col-12 col-lg-4 p-2">
                <label class="col-12 fw-bold align-middle m-0">Cédula</label>
                <label class="cursor-pointer align-middle" onclick="CopyToClipboard(this)" title="Click para copiar en el portapapeles">
                    <?= $target_account['cedula'] ?>
                </label>
            </div>
            <div class="col-12 col-lg-4 p-2">
                <label class="col-12 fw-bold align-middle m-0">Becado</label>
                <label class="align-middle fw-bold text-<?= $target_account['scholarship_coverage'] === NULL ? 'danger' : 'success' ?>">
                    <?= $target_account['scholarship_coverage'] === NULL ? 'NO' : ($target_account['scholarship'] . ' ' . $target_account['scholarship_coverage'] . '%') ?>
                </label>
            </div>
        </section>


        <section class="x_panel d-flex row m-0 p-0 py-2 col-12 col-lg-4 justify-content-center align-items-start text-center">
            <form class="row col-12 m-0 p-0 justify-content-center" action="<?= $base_url ?>/controllers/update_remote_payment.php" method="POST">
                <input type="hidden" name="id" value="<?= $target_payment['id'] ?>">

                <div class="row col-12 m-0 p-0 align-items-center justify-content-center my-2 my-lg-1 px-3 px-lg-0">
                    <label class="fw-bold col-12 col-lg-4 text-center text-lg-right align-middle m-0" for="ref">Referencia</label>
                    <input onclick="ClickingPaymentInput('ref')" class="cursor-pointer form-control col-10 col-lg-6" name="ref" id="ref" type="text" value="<?= $target_payment['ref'] ?>" readonly>
                    <i onclick="ToggleInput('ref')" class="col-1 fa fa-pencil"></i>
                </div>

                <div class="row col-12 m-0 p-0 align-items-center justify-content-center my-2 my-lg-1 px-3 px-lg-0">
                    <label class="fw-bold col-12 col-lg-4 text-center text-lg-right align-middle m-0" for="ref">Cédula / Rif</label>
                    <input onclick="ClickingPaymentInput('document')" class="cursor-pointer form-control col-10 col-lg-6" name="document" id="document" type="text" value="<?= $target_payment['document'] ?>" readonly>
                    <i onclick="ToggleInput('document')" class="col-1 fa fa-pencil"></i>
                </div>

                <div class="row col-12 m-0 p-0 align-items-center justify-content-center my-2 my-lg-1 px-3 px-lg-0">
                    <label class="fw-bold col-12 col-lg-4 text-center text-lg-right align-middle m-0" for="ref">Fecha</label>
                    <input onclick="ClickingPaymentInput('date')" class="cursor-pointer form-control col-10 col-lg-6" name="date" id="date" type="date" value="<?= $target_payment['date'] ?>" readonly>
                    <i onclick="ToggleInput('date')" class="col-1 fa fa-pencil"></i>
                </div>

                <div class="row col-12 m-0 p-0 align-items-center justify-content-center my-2 my-lg-1 px-3 px-lg-0">
                    <label class="fw-bold col-12 col-lg-4 text-center text-lg-right align-middle m-0" for="ref">Monto</label>
                    <input onclick="ClickingPaymentInput('price')" class="cursor-pointer form-control col-10 col-lg-6" name="price" id="price" type="number" value="<?= $target_payment['price'] ?>" readonly>
                    <i onclick="ToggleInput('price')" class="col-1 fa fa-pencil"></i>
                </div>

                <div class="row col-12 m-0 p-0 align-items-center justify-content-center my-2 my-lg-1 px-3 px-lg-0">
                    <label class="fw-bold col-12 col-lg-4 text-center text-lg-right align-middle m-0">Estado</label>
                    <label class="fw-bold col-10 col-lg-6 text-center text-lg-left align-middle m-0 p-0 <?= $target_payment['state'] ?>">
                        <?= $target_payment['state'] ?>
                    </label>
                    <i class="col-1"></i>
                </div>

                <div class="row col-12 m-0 p-0 align-items-start justify-content-center my-2 my-lg-1 px-3 px-lg-0">
                    <label class="fw-bold col-12 col-lg-4 text-center text-lg-right align-start m-0">Método de pago</label>
                    <label class="col-10 col-lg-6 text-center text-lg-left align-middle m-0 p-0 border border-black p-1">
                        <?php 
                            if($target_payment['payment_method_type'] === 'mobile_payment')
                                echo 'Pago móvil';
                            else if($target_payment['payment_method_type'] === 'transfer')
                                echo 'Transferencia';
                        ?>

                        <br>
                        <label class="m-0 p-0 cursor-pointer" onclick="CopyToClipboard(this)" title="Click para copiar en el portapapeles">
                            <?= $payment_method['bank'] ?>
                        </label>
                        <br>
                        <label class="m-0 p-0 cursor-pointer" onclick="CopyToClipboard(this)" title="Click para copiar en el portapapeles">
                            <?= $payment_method['document_letter'] . '-' . $payment_method['document_number'] ?>
                        </label>                        
                        <br>
                        
                        <label class="m-0 p-0 cursor-pointer" onclick="CopyToClipboard(this)" title="Click para copiar en el portapapeles">
                            <?php
                                if($target_payment['payment_method_type'] === 'mobile_payment') { 
                                    echo $payment_method['phone'];
                                }    
                                else if($target_payment['payment_method_type'] === 'transfer')
                                    echo $payment_method['account_number'];
                            ?>
                        </label>
                    </label>
                    <i class="col-1"></i>
                </div>

                <div class="row col-12 m-0 p-0 align-items-center justify-content-center my-2 my-lg-1 px-3 px-lg-0">
                    <label class="fw-bold col-12 col-lg-4 text-center text-lg-right align-middle m-0"></label>
                    <label class="fw-bold col-10 col-lg-6 text-center text-lg-left align-middle m-0 p-0">
                        
                    </label>
                    <i class="col-1"></i>
                </div>

                <div class="row col-12 m-0 p-0 align-items-center justify-content-center my-2 my-lg-1 px-3 px-lg-0">
                    <label class="fw-bold col-12 col-lg-4 text-center text-lg-right align-middle m-0">Registrado el</label>
                    <label class="col-10 col-lg-6 text-center text-lg-left align-middle m-0 p-0">
                        <?= date('d/m/Y H:i:s', strtotime($target_payment['created_at'])); ?>
                    </label>
                    <i class="col-1"></i>
                </div>

                <div class="row col-12 m-0 p-0 align-items-center justify-content-center my-2 my-lg-1 px-3 px-lg-0">
                    <label class="fw-bold col-12 col-lg-4 text-center text-lg-right align-middle m-0">Respuesta</label>
                    <label class="col-10 col-lg-6 text-center text-lg-left align-middle m-0 p-0">
                        <?= $target_payment['response'] ?>
                    </label>
                    <i class="col-1"></i>
                </div>

                <div class="row col-12 m-0 p-0 align-items-center justify-content-center mt-5">
                    <button class="btn btn-success" type="submit">
                        Modificar
                    </button>
                </div>
            </form>
        </section>

        <section class="x_panel d-flex row m-0 p-2 col-12 col-lg-4 justify-content-center text-center">
            <?php if(file_exists('../../images/payments_captures/' . $target_payment['capture'])) { ?>
                <img class="border border-black p-0 col-12 cursor-pointer payment-capture" src="<?= $base_url ?>/images/payments_captures/<?= $target_payment['capture'] ?>" alt="Comprobante de pago" onclick="ZoomInImage(this)">
            <?php } else { ?>
                <h3>Imagen <strong><?= $target_payment['capture'] ?></strong> no encontrada</h3>
            <?php } ?>
        </section>


        <section class="x_panel d-flex row m-0 p-0 col-12 col-lg-4 justify-content-center align-items-start text-center p-2">
            <div class="col-12 row m-0 p-0 justify-content-center mt-3">
                <h3>Coincidencias por fecha y referencia</h3>
            </div>
            <div class="col-12 row m-0 p-0 justify-content-center" id="unknown_incomes_container">
                <?php if($coincidences === []) { ?>
                    <h3 class="col-12 text-center text-danger">No se encontraron coincidencias</h3>
                <?php } else { ?>
                    <div class="col-12 row m-0 p-0 mt-3 justify-content-center">
                        <table class="table table-bordered shadowed coincidence-table h6" id="coincidence-0">
                            <tr>
                                <td class="bg-theme text-white fw-bold align-middle col-3">
                                    <label class="m-0" style="padding-right:10px !important" for="r-0">Sin coincidencias</label>
                                </td>
                                <td class="align-middle p-0">
                                    <input style="transform:scale(1.4)" type="radio" name="unknown-income" id="r-0" value="0" checked onchange="SelectUnknownPayment(this.id)">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <script>                       
                        function SelectUnknownPayment(inputId){
                            const tables = document.getElementsByClassName('coincidence-table')
                            for(let i = 0; i < tables.length; i++){
                                tables[i].classList.remove('border-success')
                            }

                            const id = inputId.split('-')[1]
                            document.getElementById('coincidence-' + id).classList.add('border-success')
                            document.getElementById('selected_income').value = id
                        } 
                    </script>
                    <?php foreach($coincidences as $coincidence) { ?>
                        <?php 
                            $matchs = intval($coincidence['remote_payment']) === intval($target_payment['id']);
                            if($matchs)
                                $payment_identified = true;
                        ?>
                        <div class="col-12 row m-0 p-0 my-2">
                            <table class="table table-bordered shadowed coincidence-table h6" id="coincidence-<?= $coincidence['id'] ?>">
                                <tr>
                                    <td class="bg-theme text-white fw-bold align-middle col-3 border-black">Referencia</td>
                                    <td class="border-black"><?= $coincidence['ref'] ?></td>
                                </tr>
                                <tr>
                                    <td class="bg-theme text-white fw-bold align-middle col-3 border-black">Fecha</td>
                                    <td class="border-black"><?= date('d/m/Y', strtotime($coincidence['date'])) ?></td>
                                </tr>
                                <tr>
                                    <td class="bg-theme text-white fw-bold align-middle col-3 border-black">Monto</td>
                                    <td class="border-black"><?= GetPrettyCiphers($coincidence['price']) ?></td>
                                </tr>
                                <tr>
                                    <td class="bg-theme text-white fw-bold align-middle col-3 border-black">Propietario</td>
                                    <td class="border-black fw-bold">
                                        <?php if($coincidence['remote_payment'] === null){ ?>
                                            <span class="text-danger">Sin identificar</span>
                                        <?php } else { ?>
                                            <a target="_blank" class="text-decoration-underline text-black" href=" <?= $base_url ?>/views/forms/update_remote_payment.php?id=<?= $coincidence['remote_payment'] ?>">
                                                <?= $coincidence['surnames'] . ' ' . $coincidence['names'] . ' (' . $coincidence['cedula'] .  ')' ?>
                                                <i class="fa fa-reply"></i>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-theme text-white fw-bold align-middle col-3 border-black">Descripción</td>
                                    <td class="border-black"><?= $coincidence['description'] ?></td>
                                </tr>
                                <tr>
                                    <td class="bg-theme text-white fw-bold align-middle col-3 border-black">
                                        <label class="m-0 p-0" style="padding-right:10px !important" for="r-<?= $coincidence['id'] ?>">Seleccionar</label>
                                    </td>
                                    <td class="border-black">
                                        <?php if($matchs === true || $coincidence['remote_payment'] === null) { // No está identificado y además no coincide ?>
                                            <input 
                                                style="transform:scale(1.4)"
                                                type="radio" 
                                                name="unknown-income" 
                                                id="r-<?= $coincidence['id'] ?>" 
                                                value="<?= $coincidence['id'] ?>" 
                                                <?= $matchs ? 'checked' : '' ?>   
                                                onchange="SelectUnknownPayment(this.id)"
                                            >
                                        <?php } else { ?>
                                            <span class="fw-bold m-0 text-center text-danger">
                                                YA ESTÁ IDENTIFICADO
                                            </span>
                                        <?php } ?>
                                    </td>
                                    <?php if($matchs) { ?>
                                        <script>
                                            SelectUnknownPayment('r-<?= $coincidence['id'] ?>')
                                        </script>
                                    <?php } ?>
                                </tr>
                            </table>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </section>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <section class="col-12 row justify-content-center h6 bg-white py-2" style="border: 1px solid #d6d6d6ff !important">
            <h2 class="col-12 h2">
                Productos
            </h2>

            <div class="row col-12">
                <div class="table-responsive">
                    <table class="table table-striped col-12">
                        <thead class="bg-theme text-white fw-bold h6">
                            <tr class="text-center">
                                <th>Producto</th>
                                <th>Monto ($)</th>
                                <th>Tasa</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $products_total = 0; ?>
                            <?php foreach($products as $product) { ?>
                                <?php 
                                    $total = round($product['price'] * $coinValues['Dólar'], 2); 
                                    $products_total += $total; 
                                ?>
                                <tr class="text-center">
                                    <td class="align-middle text-right"><?= $product['product'] ?></td>
                                    <td class="align-middle text-right"><?= $product['price'] ?></td>
                                    <td class="align-middle text-right"><?= $coinValues['Dólar'] ?></td>
                                    <td class="align-middle text-right" id="<?= $product['product'] ?>">Bs. <?= GetPrettyCiphers(round($total, 2)) ?> </td>
                                </tr>
                            <?php } ?>
                            <tr class="fw-bold text-right bg-theme text-white h5">
                                <td class="align-middle" colspan="3">Total</td>
                                <td>Bs. <?= GetPrettyCiphers(round($products_total, 2)) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>        
    </div>

    <?php if(!in_array($_SESSION['neocaja_rol'], ['SENIAT'])) { ?>
        <form class="col-12 row m-0 p-0 justify-content-center confirm-form" method="POST" action="<?=$base_url?>/controllers/update_remote_payment_state.php">
            <input type="hidden" name="id" value="<?= $target_payment['id'] ?>">
            <input type="hidden" id="selected_income" name="selected_income" value="<?= $payment_identified ? $coincidence['id'] : '' ?>">

            <section class="col-12 col-lg-6 row justify-content-center h6 bg-white py-2" style="border: 1px solid #d6d6d6ff !important">
                <div class="row col-12 justify-content-center my-5 confirm-form" >
                    <div class="row col-10 justify-content-center align-items-center my-2 mb-5 mb-lb-2">
                        <label class="fw-bold col-12 col-lg-4 text-center text-lg-right m-0 align-middle" for="state">
                            Actualizar estado a
                        </label>
                        <select class="form-control col-12 col-lg-5" name="state" id="state" required onchange="DispalyDefaultResponse(this)">
                            <option value=""></option>
                            <option value="Conciliado">Conciliado</option>
                            <option value="Rechazado">Rechazado</option>
                        </select>
                    </div>
    
                    <div class="row col-10 justify-content-center align-items-start my-2">
                        <label class="col-12 col-lg-3 fw-bold text-center text-lg-right" for="response">
                            Respuesta
                        </label>
                        <textarea class="col-12 col-lg-8 form-control" name="response" id="response" required></textarea>
                    </div>
    
                    <div class="row col-10 justify-content-start align-items-start my-2">
                        <button class="btn btn-success mx-auto">
                            Proceder
                        </button>
                    </div>
                </div>
            </section>    
        </form>
    <?php } ?>
</div>

<?php if($payment_identified === false) { ?>
<script>
    SelectUnknownPayment('r-0')
</script>
<?php } ?>


<?php include '../common/footer.php'; ?>

<script>
    document.getElementById('price').innerHTML = 'Bs. ' + GetPrettyCiphers('<?= $target_payment['price'] ?>')

    function DispalyDefaultResponse(select){
        const value = select.value
        const textarea = document.getElementById('response')

        textarea.value = ''
        if(select.value === '')
            return

        
        if(select.value === 'Conciliado')
            textarea.value = 'Su pago ha sido conciliado.'
    }   
    
    function ToggleInput(id){
        const input = document.getElementById(id)
        input.readOnly = !input.readOnly

        if(input.classList.contains('cursor-pointer'))
            input.classList.remove('cursor-pointer')
        else
            input.classList.add('cursor-pointer')
    }

    function ClickingPaymentInput(id){
        const input = document.getElementById(id)
        if(input.readOnly === false)
            return

        CopyToClipboard(input)
    }

</script>

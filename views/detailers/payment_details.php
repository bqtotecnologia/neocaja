<?php 
$admitted_user_types = ['Cajero', 'Super', 'Estudiante'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';
include_once '../../utils/Validator.php';
include_once '../../utils/prettyCiphers.php';

$id = Validator::ValidateRecievedId();
$error = '';

if(is_string($id)){
    $error = $id;    
}

include_once '../../models/account_payments_model.php';
include_once '../../models/account_model.php';
include_once '../../models/product_model.php';
include_once '../../models/coin_model.php';

$payment_model = new AccountPaymentsModel();
$account_model = new AccountModel();
$siacad = new ProductModel();
$coin_model = new CoinModel();

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
$coinValues = $coin_model->GetCoinValuesOfDate(date('Y-m-d', strtotime($target_payment['created_at'])));

include '../../views/common/header.php';

?>

<div class="row justify-content-center">
    
    <div class="col-12 row justify-content-center">
        <?php 
        if($_SESSION['neocaja_rol'] === 'Estudiante')
            $btn_url = $base_url . '/views/tables/my_payments.php'; 
        else
            $btn_url = $base_url . '/views/panel.php'; 
        include_once '../layouts/backButton.php'; 
        ?>
    </div>

    <div class="col-12 text-center mt-4">
        <h1 class="h1 text-black">Pago remoto</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <section class="col-12 row justify-content-center h6 bg-white py-2" style="border: 1px solid #d6d6d6ff !important">
            <div class="row col-6 justify-content-center align-self-start h5">
                <div class="row col-10 justify-content-start align-items-start">
                    <label class="fw-bold mx-2">
                        Cliente:
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

                <div class="row col-10 justify-content-start align-items-middle border border-black p-1 m-1">
                    <label class="fw-bold mx-2">
                        Método de pago:
                    </label>
                    <span class="">
                        <?php
                            $display = '';
                            if($target_payment['payment_method_type'] === 'mobile_payment'){
                                $display .= 'Pago móvil <br> ' . $payment_method['phone'];
                            }
                            else if($target_payment['payment_method_type'] === 'transfer'){
                                $display .= 'Transferencia <br> ' . $payment_method['account_number'];
                            }

                            $display .= '<br>' . $payment_method['document_letter'] . $payment_method['document_number'];                            
                            $display .= '<br>' . $payment_method['bank'];

                            echo $display;
                        ?>
                    </span>
                </div>              
            </div>

            <div class="row col-6 justify-content-center align-self-start h5">               

                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Referencia:
                    </label>
                    <span class="">
                        <?= $target_payment['ref'] ?>
                    </span>
                </div>
                
                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Monto:
                    </label>
                    <span class="" id="price">                        
                    </span>
                </div>

                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Cédula/Rif:
                    </label>
                    <span class="">
                        <?= $target_payment['document'] ?>
                    </span>
                </div>

                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Fecha:
                    </label>
                    <span class="">
                        <?= date('d/m/Y', strtotime($target_payment['created_at'])) ?>
                    </span>
                </div>

                <div class="row col-10 justify-content-start align-items-middle">
                    <label class="fw-bold mx-2">
                        Estado:
                    </label>
                    <span class="fw-bold <?= $target_payment['state'] ?>">
                        <?= $target_payment['state'] ?>
                    </span>
                </div>
            </div>

            <div class="row col-12 justify-content-center align-self-start my-4">
                <div class="row col-10 justify-content-start align-items-center p-2">
                    <label class="fw-bold mx-2 my-0 h4">
                        Respuesta:
                    </label>
                    <span class="h4 m-0">
                        <?= $target_payment['response'] ?? 'Sin Respuesta' ?>
                    </span>
                </div>
            </div>
        </section>


        <section class="col-12 row justify-content-center h6 bg-white py-2" style="border: 1px solid #d6d6d6ff !important">
            <h2 class="col-12 h2">
                Productos
            </h2>

            <div class="row col-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
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
                                    <td><?= $product['product'] ?></td>
                                    <td class="text-right"><?= $product['price'] ?></td>
                                    <td class="text-right"><?= $coinValues['Dólar'] ?></td>
                                    <td class="text-right" id="<?= $product['product'] ?>">Bs. <?= GetPrettyCiphers(round($total, 2)) ?> </td>
                                </tr>
                            <?php } ?>
                            <tr class="fw-bold text-right">
                                <td colspan="3">Total:</td>
                                <td>Bs. <?= GetPrettyCiphers(round($products_total, 2)) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>        
    </div>

    <?php if($_SESSION['neocaja_rol'] !== 'Estudiante') { ?>
        <section class="col-10 col-lg-6 row justify-content-center h6 bg-white py-2" style="border: 1px solid #d6d6d6ff !important">
            <form class="row col-12 justify-content-center my-5 confirm-form" method="POST" action="<?=$base_url?>/controllers/update_account_payment.php">
                <input type="hidden" name="id" value="<?= $target_payment['id'] ?>">
                <div class="row col-10 justify-content-center align-items-center my-2">
                    <label class="fw-bold col-12 col-lg-4 text-right m-0 align-middle" for="state">
                        Actualiar estado a:
                    </label>
                    <select class="form-control col-6" name="state" id="state" required onchange="DispalyDefaultResponse(this)">
                        <option value=""></option>
                        <option value="Aprobado">Aprobado</option>
                        <option value="Rechazado">Rechazado</option>
                    </select>
                </div>

                <div class="row col-10 justify-content-start align-items-start my-2">
                    <label class="fw-bold mx-2" for="response">
                        Respuesta:
                    </label>
                    <textarea class="col-12 form-control" name="response" id="response" required></textarea>
                </div>

                <div class="row col-10 justify-content-start align-items-start my-2">
                    <button class="btn btn-success mx-auto">
                        Procesar pago
                    </button>
                </div>
            </form>
        </section>
    <?php } ?>
</div>


<?php include '../common/footer.php'; ?>
<script>
    document.getElementById('price').innerHTML = 'Bs. ' + GetPrettyCiphers('<?= $target_payment['price'] ?>')

    function DispalyDefaultResponse(select){
        const value = select.value
        const textarea = document.getElementById('response')

        textarea.value = ''
        if(select.value === '')
            return

        
        if(select.value === 'Aprobado')
            textarea.value = 'Pago verificado exitosamente. Acuda al instituto para retirar su factura.'
    }
</script>
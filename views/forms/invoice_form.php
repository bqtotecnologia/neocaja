<?php
$admitted_user_types = ['Super', 'Cajero'];
include_once '../../utils/validate_user_type.php';
include_once '../../utils/base_url.php';

include_once '../common/header.php';

include_once '../../models/account_model.php';
include_once '../../models/bank_model.php';
include_once '../../models/sale_point_model.php';
include_once '../../models/payment_method_model.php';
include_once '../../models/coin_model.php';
include_once '../../models/product_model.php';
include_once '../../models/siacad_model.php';

$account_model = new AccountModel();
$bank_model = new BankModel();
$sale_point_model = new SalePointModel();
$payment_method_model = new PaymentMethodModel();
$coin_model = new CoinModel();
$product_model = new ProductModel();
$siacad = new SiacadModel();

$accounts = $account_model->GetAccounts();
$banks = $bank_model->GetActivebanks();
$sale_points = $sale_point_model->GetSalePoints();
$payment_methods = $payment_method_model->GetAllPaymentMethodTypes();
$coins = $coin_model->GetActiveCoins();
$products = $product_model->GetActiveProducts();
$period = $siacad->GetCurrentPeriodo();
$periodId = $period['idperiodo'];

// onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46)"

?>

<div class="row justify-content-center">
    <div class="col-12 row justify-content-center x_panel">
        <?php $btn_url = '../tables/search_invoices_of_today.php'; include_once '../layouts/backButton.php'; ?>
    </div>
    
    <div class="col-12 justify-content-center px-5 mt-4">            
        <form action="../../controllers/handle_invoice.php" method="POST" id="" class="d-flex justify-content-center align-items-center flex-column x_panel confirm-form ">
            <div class="col-12 text-center">
                <h1 class="h1 text-black">Registrar nueva factura</h1>
            </div>

            <div class="row col-12 m-0 p-0 justify-content-center align-items-start">
                <div class="row col-12 col-md-6 my-2 justify-content-start">
                    <div class=" row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4 ">
                        <label class=" h6 m-0 fw-bold px-2 " for="account">Cliente</label>
                    </div>
                    <div class=" row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start ">
                        <div class="row m-0 p-0 col-12 col-md-8">
                            <select id="account" name="account" class="form-control col-10 col-md-8 select2">
                                <option value="">&nbsp;</option>
                                <?php foreach($accounts as $account) { ?>
                                    <option value="<?= $account['id'] ?>">
                                        <?= '(' . $account['cedula'] . ') ' . $account['names'] . ' ' . $account['surnames'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row col-12 col-md-6 my-2 justify-content-start">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th>Fecha</th>
                                <th>Concepto</th>
                                <th>Monto</th>
                                <th>Ver</th>
                            </tr>
                        </thead>
                        <tbody id="invoice-table">
                            
                        </tbody>
                    </table>
                </div>
            </div>



        


            <div class=" row col-12 col-md-8 my-2 justify-content-start ">
                <div class=" row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4 ">
                    <label class=" h6 m-0 fw-bold px-2 " for="cedula">Cliente</label>
                </div>
                <div class=" row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start ">
                    <input id="cedula" name="cedula" class=" form-control  col-10 col-md-5" placeholder="" required="" value="" type="number"  maxlength="10" minlength="7">
                </div>
            </div>

            <div class=" row col-12 col-md-8 my-2 justify-content-start ">
                <div class=" row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4 ">
                    <label class=" h6 m-0 fw-bold px-2 " for="names">Nombres</label>
                </div>
                <div class=" row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start ">
                    <input id="names" name="names" class=" form-control  col-10 col-md-8" placeholder="Ambos nombres" required="" value="" type="text" maxlength="100" minlength="5">
                </div>
            </div>

            <div class=" row col-12 col-md-8 my-2 justify-content-start ">
                <div class=" row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4 ">
                    <label class=" h6 m-0 fw-bold px-2 " for="address">Dirección</label>
                </div>
                <div class=" row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start ">
                    <textarea id="address" name="address" class=" form-control  col-10 col-md-8" required="" rows="3" columns="50" value=""
                    ></textarea> 
                </div>
            </div>

            <div class=" row col-12 col-md-8 my-2 justify-content-start ">
                <div class=" row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4 ">
                    <label class=" h6 m-0 fw-bold px-2 " for="is_student">Es estudiante</label>
                </div>
                <div class=" row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start ">
                    <div class="row col-12 m-0 p-0 mx-2">
                        <div class="icheckbox_flat-green" style="position: relative;">
                            <input id="is_student-1" type="checkbox" name="is_student[]" value="1" class=" form-control  col-10 col-md-5 flat ">
                        </div>
                        <label for="is_student-1" class=" text-left mx-2 h6 ">Es estudiante</label>
                    </div>
                </div>
            </div>

            <div class=" row col-12 col-md-8 my-2 justify-content-start ">
                <div class=" row m-0 p-0 align-items-center justify-content-center justify-content-md-end col-12 col-md-4 ">
                    <label class=" h6 m-0 fw-bold px-2 " for="company">Empresa</label>
                </div>
                <div class=" row col-12 col-md-8 m-0 p-0 justify-content-center justify-content-md-start ">
                    <div class="row m-0 p-0 col-12 col-md-8">
                        <select id="company" name="company" class="form-control  col-10 col-md-8 select2  select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                            <option value="">&nbsp;</option>
                            <option value="1">Granel SA</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row col-12 m-0 p-0 justify-content-center">
                <button type="submit" class="btn btn-success">Registrar</button>
            </div>
        </form>
    </div>
</div>

<?php include_once '../common/footer.php'; ?>

<script>
    const invoiceTable = document.getElementById('invoice-table')
    $('.select2').on('select2:select', async function (e) {
        ClearInvoices()
        if(e.target.value !== ''){
            var result = await GetInvoicesOfAccount(e.target.value)            
            
            if(typeof result !== "string"){
                console.log(result.data.length)
                if(result.data.length > 0){
                    result.data.forEach((invoice) => {
                        AddInvoice(invoice)
                    })
                }
            }
        }
    });

    async function GetInvoicesOfAccount(account){
        var period = '<?= $periodId ?>'
        var url = '<?= $base_url ?>/api/get_invoices_of_account.php?account=' + account + '&period=' + period

        var fetchConfig = {
            method: 'GET', 
            headers: {
                'Content-Type': 'application/json',
            },
        }

        return await TryFetch(url, fetchConfig)
    }

    function AddInvoice(invoice){
        var dateCol = document.createElement('td')
        const now = new Date(invoice.created_at);
        const year = now.getFullYear();
        const month = (now.getMonth() + 1).toString().padStart(2, '0');
        const day = now.getDate().toString().padStart(2, '0');
        const formattedDate = `${day}/${month}/${year}`;
        dateCol.innerHTML = formattedDate

        var conceptCol = document.createElement('td')
        conceptCol.innerHTML = invoice.reason

        var priceCol = document.createElement('td')
        priceCol.innerHTML = invoice.total

        var seeCol = document.createElement('td')
        var seeLink = document.createElement('a')
        seeCol.href = '<?= $base_url ?>/views/detailers/see_invoice.php'
        seeCol.target = '_blank'
        seeCol.innerHTML = 'Ver'
        seeCol.appendChild(seeLink)

        var row = document.createElement('tr')
        row.appendChild(dateCol)
        row.appendChild(conceptCol)
        row.appendChild(priceCol)
        row.appendChild(seeCol)
        
        invoiceTable.appendChild(row)
    }

    function ClearInvoices(){
        for (const child of invoiceTable.children) {
            child.remove()
        }
    }


</script>
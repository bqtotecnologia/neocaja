<script>
    // CONTAINERS
    const invoiceContainer = document.getElementById('invoices')
    const debtContainer = document.getElementById('debt-container')
    const debtContent = document.getElementById('debt-content')
    const focContent = document.getElementById('foc-content')
    const scholarshipContainer = document.getElementById('scholarship')
    const companyContainer = document.getElementById('company')


    // TABLES
    const invoiceTable = document.getElementById('invoice-table')
    const productTable = document.getElementById('product-table')  
    const debtTable = document.getElementById('debt-table')
    const paymentTable = document.getElementById('payment-table')
    const igtf_table = document.getElementById('igtf-table')
    

    // CONSTS    
    const products = []
    const productPrices = {}
    const coinHistories = {}
    const productIds = {}
    const retardMaxDay = parseInt('<?= $global_vars['Dia tope mora'] ?>')
    const retardPercent = parseFloat('<?= $global_vars['Porcentaje mora'] ?>')
    const scholarship_with_retard = '<?= $global_vars['Becados pagan mora'] ?>' === '1' ? true : false
    const igtf_rate = 0.03
    const payment_method_blockable_fields = ['bank', 'salepoint', 'document']
    const payment_method_field_block = {
        'Efectivo': ['bank', 'salepoint', 'document'],
        'Pago móvil': ['salepoint'],
        'Transferencia': ['salepoint'],
        'Tarjeta de débito': [],
    }
    
    
    // LISTS
    const payment_methods = []
    const coins = []
    const banks = []
    const sale_points = []     
    

    // INPUTS    
    const rateDate = document.getElementById('rate-date')
    const igtf_method = document.getElementById('igtf-method')
    const igtf_bank = document.getElementById('igtf-bank')
    const igtf_salepoint = document.getElementById('igtf-salepoint')
    const igtf_document = document.getElementById('igtf-document')
    const igtf_price = document.getElementById('igtf-price')
    const igtf_coin = document.getElementById('igtf-coin')
    const igtf_total = document.getElementById('igtf-total')
    const incomesInput = document.getElementById('known-incomes')


    // LABELS
    const igtf_total_label = document.getElementById('igtf-total-label')    


    // CONTROL VARS
    let coinValues = {}    
    let nextProduct = 1
    let targetAccount = {}
    let paidMonths = []
    let partialMonths = []
    let periodMonths = []
    let debtData = ''   
    let target_payment = ''
    var currentDate = new Date()
    let currentMonth = currentDate.getMonth()
    let lastMonth = currentMonth
    let youngestPayableMonth = null
    let yearOfNextMonth = currentDate.getFullYear()
    let monthReached = false
    let nextPaymentMethod = 1
    let scholarshipped = false
    let igtfVisible = false

    // EVENTS
    igtf_method.addEventListener('change', function(e) { IGTFPaymentMethodSelecting(e) })
</script>



<?php foreach($products as $product) { ?>
    <script>
        product = {
            'name': '<?= $product['name'] ?>',
            'id': '<?= $product['id'] ?>',
        }
        products.push(product)

        productPrices['<?= $product['name'] ?>'] = parseFloat('<?= $product['price'] ?>')
        productIds['<?= $product['name'] ?>'] = '<?= $product['id'] ?>'

        if(product['name'] === 'Mensualidad')
            monthlyId = String(product['id'])
    </script>
<?php } ?>


<?php foreach($coinHistories as $coin => $history) { if($coin === 'Bolívar') continue; ?>
    <script>
        coinHistories['<?= $coin ?>'] = []        
    </script>

    <?php foreach($history as $point) { ?>
        <script>
            var to_add = {
                'date': '<?= date('d/m/Y', strtotime($point['created_at'])) ?>',
                'value': '<?= $point['price'] ?>'
            }
            coinHistories['<?= $coin ?>'].push(to_add)
        </script>
    <?php } ?>
<?php } ?>


<?php foreach($banks as $bank) { ?>
    <script>
        bank = {
            'name': '<?= $bank['name'] ?>',
            'id': '<?= $bank['id'] ?>',
        }
        banks.push(bank)
    </script>
<?php } ?>


<?php foreach($coins as $coin) { ?>
    <script>
        coin = {
            'name': '<?= $coin['name'] ?>',
            'id': '<?= $coin['id'] ?>',
        }
        coins.push(coin)

        coinValues['<?= $coin['name'] ?>'] = parseFloat('<?= $coin['price'] ?>')
    </script>
<?php } ?>


<?php foreach($payment_methods as $method) { ?>
    <script>
        payment_method = {
            'name': '<?= $method['name'] ?>',
            'id': '<?= $method['id'] ?>',
        }
        payment_methods.push(payment_method)
    </script>
<?php } ?>


<?php foreach($sale_points as $sale_point) { ?>
    <script>
        sale_point = {
            'code': '<?= $sale_point['code'] ?>',
            'id': '<?= $sale_point['id'] ?>',
            'bank': '<?= $sale_point['bank_id'] ?>'
        }
        sale_points.push(sale_point)
    </script>
<?php } ?>
<script>
    const invoiceContainer = document.getElementById('invoices')
    const invoiceTable = document.getElementById('invoice-table')
    const productTable = document.getElementById('product-table')
    const debtContainer = document.getElementById('debt-container')
    const debtContent = document.getElementById('debt-content')
    const debtTable = document.getElementById('debt-table')
    const focContent = document.getElementById('foc-content')

    const products = []
    const productPrices = {}
    const coinHistories = {}
    const productIds = {}

    const retardMaxDay = parseInt('<?= $global_vars['Dia tope mora'] ?>')
    const retardPercent = parseFloat('<?= $global_vars['Porcentaje mora'] ?>')

    const months = {
        '1': 'Enero',
        '2': 'Febrero',
        '3': 'Marzo',
        '4': 'Abril',
        '5': 'Mayo',
        '6': 'Junio',
        '7': 'Julio',
        '8': 'Agosto',
        '9': 'Septiembre',
        '10': 'Octubre',
        '11': 'Noviembre',
        '12': 'Diciembre',
    }    

    const month_translate = {
        'Enero': 1,
        'Febrero': 2,
        'Marzo': 3,
        'Abril': 4,
        'Mayo': 5,
        'Junio': 6,
        'Julio': 7,
        'Agosto': 8,
        'Septiembre': 9,
        'Octubre': 10,
        'Noviembre': 11,
        'Diciembre': 12
    }

    
    let nextProduct = 1
    let targetAccount = {}
    let periodMonths = []
    

    var currentDate = new Date()
    let currentMonth = currentDate.getMonth()
    let lastMonth = currentMonth
    let yearOfNextMonth = currentDate.getFullYear()
    let monthReached = false
    let paidMonths = []
    let debtData = ''
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


<script>  
    $('#account').on('select2:select', async function (e) {
        ClearInvoices()
        if(e.target.value !== ''){
            await AccountSelecting(e)
        }
    });

    async function AccountSelecting(e){
        var accountButton = document.getElementById('account-link')
        accountButton.classList.add('d-none')
        debtContainer.classList.add('d-none')
        var accountMonths = await GetAccountState(e.target.value, '<?= $periodId ?>')
        invoiceTable.innerHTML = ''
        CleanProducts()
            
        if(typeof accountMonths !== "string"){
            await DisplayDebt(e.target.value, '<?= $periodId ?>')
            targetAccount = await GetAccountData(e.target.value)
            targetAccount = targetAccount.data
            accountButton.classList.remove('d-none')
            accountButton.href  = '<?= $base_url ?>' + '/views/detailers/account_details.php?id=' + targetAccount.id
            await DisplayInvoices(accountMonths.data)
        }

        AddProduct()
        DisplayDefaultProduct()        
        if(typeof debtData !== "string"){
            UpdateDefaultProducts(debtData.data)
        }
        UpdateProductsPrice()
    }    

    async function DisplayInvoices(invoices){
        if(Object.keys(invoices).length > 0){
            invoiceContainer.classList.remove('d-none')
            for(let key in invoices){
                AddInvoice(key, invoices[key])
            }
        }
    }

    function ClearInvoices(){
        for (const child of invoiceTable.children) {
            child.remove()
        }
    }

    async function DisplayDebt(account, period){
        debtData = await GetDebtOfAccountOfPeriod(account,period)        
        if(typeof debtData !== "string"){
            BuildDebtTable(debtData.data)
        }
    }

    function GetPrettyCiphers(cipher){
        buffer = parseFloat(cipher).toFixed(2)
        var strCipher = String(buffer).replace('.', ',')
        var splits = strCipher.split(',')
        var intPart = splits[0]
        var decimalPart = splits[1]
        

        var finalStr = ''
        var positionCount = 0
        for(let i = intPart.length - 1; i >= 0; i--){
            var display = intPart[i]

            positionCount++
            if(positionCount === 4)
                display = display + '.'

            finalStr = display + finalStr
        }

        if(decimalPart !== undefined)
            finalStr = finalStr + ','  + decimalPart
        return finalStr
    }

    function DisplayCoinHistory(coin){
        var text = `<table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tasa</th>
                </tr>
            </thead>
            <tbody>`

        coinHistories[coin].forEach((history) => {
            text += 
                `<tr>
                    <td>` + history['date'] + `</td>
                    <td>` + history['value'] + `</td>
                </tr>`
        })

        text += `</tbody></table>`

        Swal.fire({
            title: '7 tasas más recientes del ' + coin,
            html:text
        })
    }

    function UpdateDefaultProducts(debt){
        var firstProduct = document.getElementById('product-baseprice-1')
        var secondProduct = document.getElementById('product-baseprice-2')
        if(debt.months > 0 && debt.retard > 0){
            // Tiene tanto mora como deuda
            firstProduct.value = debt.retard
            secondProduct.value = debt.months            
        }
        else if(debt.months > 0){
            // Aún debe el mes pero no tiene mora
            firstProduct.value = debt.months
        }
    }

    function AddProduct(){
        BuildProductRow()
        $(".select2").select2({width:'100%'});

        var oldId = nextProduct
        $('#product-id-' + String(nextProduct)).on('select2:select', async function (e) {
            ProductSelecting(oldId, e)
        })

        nextProduct++
    }

    function ProductSelecting(oldId, e){                
        var price = 0
        var productName = null

        for(let i = 0; i < e.target.childNodes.length; i++){
            // searching the product name to get it's price
            var node = e.target.childNodes[i]
            if(node.selected){
                productName = node.innerHTML
            }
        }

        var total = 0

        if(productName === '&nbsp;')
            price = 0
        else
            price = productPrices[productName]

        var priceInput = document.getElementById('product-baseprice-' + String(oldId))
        priceInput.value = price
        total = price
        
        if(targetAccount['scholarship_coverage'] !== null && targetAccount['scholarship_coverage'] !== undefined ){
            total = parseFloat(price) * (parseFloat(targetAccount['scholarship_coverage']) / 100)
        }
        
        var productTotalInput = document.getElementById('product-total-' + String(oldId))
        productTotalInput.value = total    
        UpdateProductsPrice()
    }

    function UpdateProductsPrice(forceUpdatePrice = false){
        UpdateScholarshipValues()

        for(let i = 0; i <= nextProduct; i++){
            var productBasePriceInput = document.getElementById('product-baseprice-' + String(i))            

            if(productBasePriceInput === null)
                continue

            var productSelect = document.getElementById('product-id-' + String(i))
            var productName = productSelect.options[productSelect.selectedIndex].innerHTML
            if(productName === '&nbsp;' || productName === '')
                continue

            var productBasePrice = productPrices[productName]

            if(!forceUpdatePrice)
                productBasePrice = parseFloat(productBasePriceInput.value)

            var monthInput = document.getElementById('product-month-' + i)
            var targetOption = monthInput.options[monthInput.selectedIndex]
            
            if(productName === 'Diferencia Mensualidad'){
                // Add retard
                var monthlyPrice = productPrices['Mensualidad']
                productBasePrice = monthlyPrice * (retardPercent / 100)
            }

            if(forceUpdatePrice)
                productBasePriceInput.value = productBasePrice

            var productSholarship = document.getElementById('product-scholarship-' + String(i))
            var discount = parseFloat(productSholarship.value.trim('%'))

            var productTotal = (productBasePrice - (productBasePrice * (discount / 100))).toFixed(2)
            document.getElementById('product-total-' + String(i)).value = productTotal          
        }
        
        UpdateProductTotal()
    }

    function UpdateScholarshipValues(){
        var scholarshipValue = '0%'
        var scholarships = document.getElementsByClassName('scholarship-input')
        if(targetAccount['scholarship_coverage'] !== null && targetAccount['scholarship_coverage'] !== undefined )
            scholarshipValue = String(targetAccount['scholarship_coverage']) + '%'

        for (let i = 0; i < scholarships.length; i++) {               
            scholarships[i].value = scholarshipValue
        }
    }

    function UpdateProductTotal(){
        var productsTotal = document.getElementById('products-total')
        var productsTotalBs = document.getElementById('products-total-bs')
        var usdRate = document.getElementById('Dólar-rate').innerHTML
        var total = 0
        
        // adding all totals of products
        for (let i = 0; i <= nextProduct; i++) {
            var priceInput = document.getElementById('product-total-' + i)
            if(priceInput === null || priceInput.value === "")
                continue
            
            var price = parseFloat(priceInput.value)          
            total += price
        }

        productsTotal.innerHTML = total + '$'
        productsTotalBs.innerHTML = (total * parseFloat(usdRate)).toFixed(2)
        UpdatePaymentMethodsDiffWithProducts()
    }

    function DisplayDefaultProduct(){      
        if(nextProduct !== 2)
            return

        var nextMonth = parseInt(lastMonth)
        /*
        if(nextMonth >= 12){
            nextMonth = 1
            yearOfNextMonth += 1
        }
            */
        
        console.log('Mes atrasado: ' + yearOfNextMonth)
        if(GetMonthIsRetarded(nextMonth)){
            ChangeMonth(nextMonth, nextProduct - 1)
            ChangeProduct(nextProduct - 1, productIds['Diferencia Mensualidad'])
            AddProduct()
        }

        ChangeMonth(nextMonth, nextProduct - 1)
        ChangeProduct(nextProduct - 1, productIds['Mensualidad'])        

        UpdateProductsPrice(true)
    }

    function ChangeMonth(month, id){      
        $('#product-month-' + (nextProduct - 1)).val(String(month)) 
    }

    function ChangeProduct(position, productId){
        $('#product-id-' + position).select2("val", String(productId))
    }

    function ToggleReadOnly(input){
        input.readOnly = !input.readOnly
    }

    function CleanProducts(){
        lastMonth = currentMonth
        monthReached = false
        productTable.innerHTML = ''
        nextProduct = 1
        paidMonths = []
        updatePricesAccordToDebt = false
        periodMonths = []
        //AddProduct()
    }

    function DeleteProductRow(id){
        document.getElementById('product-row-' + id).remove()
        UpdateProductTotal()
    }

    function GetMonthIsRetarded(targetMonth){
        var currentDate = new Date()
        var todayMonth = currentDate.getMonth() + 1
        result = false
        if(parseInt(targetMonth) < todayMonth && yearOfNextMonth === currentDate.getFullYear()){
            result = true
        }
        else if(parseInt(targetMonth) === todayMonth && parseInt(currentDate.getDate()) > retardMaxDay){
            result = true
        }
        return result
    }
</script>
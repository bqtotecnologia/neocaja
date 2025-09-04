<script>
    const paymentTable = document.getElementById('payment-table')

    const payment_methods = []
    const coins = []
    const banks = []
    const sale_points = [] 

    let nextPaymentMethod = 1
</script>

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
        }
        sale_points.push(sale_point)
    </script>
<?php } ?>


<script>  
    AddPayment() 

    function AddPayment(){
        BuildPaymentMethodRow()
        $(".select2").select2({width:'100%'});

        var oldId = nextPaymentMethod
        nextPaymentMethod++
    }

    function RefreshPaymentMethods(){
        for(let i = 0; i < nextPaymentMethod; i++){
            const targetElement = document.getElementById('payment-coin-' + String(i))
            if(targetElement !== null)
                UpdatePaymentPrice(i)
        }
        UpdatePaymentTotal()
    }

    function CoinSelecting(oldId, e){   
        var coinSelect = document.getElementById('payment-coin-' + String(oldId))
        for(let i = 0; i < coinSelect.childNodes.length; i++){
            var node = coinSelect.childNodes[i]
            if(node.selected){
                coinName = node.innerHTML
            }
        }

        var igtf_table = document.getElementById('igtf-table')
        if(coinName !== 'Bolívar' && coinName !== '&nbsp;'){
            igtf_table.classList.remove('d-none')
        }
        else{
            igtf_table.classList.add('d-none')
        }


        UpdatePaymentPrice(oldId)
        UpdatePaymentTotal()
    }

    function UpdatePaymentPrice(id){
        var rate = 0
        var coinName = null

        var coinSelect = document.getElementById('payment-coin-' + String(id))
        for(let i = 0; i < coinSelect.childNodes.length; i++){
            var node = coinSelect.childNodes[i]
            if(node.selected){
                coinName = node.innerHTML
            }
        }

        if(coinName !== '&nbsp;')
            rate = coinValues[coinName]

        //var rateInput = document.getElementById('payment-rate-' + String(id))
        //rateInput.value = rate

        var price = 0
        var priceInput = document.getElementById('payment-price-' + String(id))
        if(priceInput.value !== '')            
            price = parseFloat(priceInput.value)

        var total = parseFloat(price * rate).toFixed(2)
        
        var paymentTotalInput = document.getElementById('payment-total-' + String(id))
        paymentTotalInput.value = total   
    }

    function UpdatePaymentTotal(){
        var paymentTotal = document.getElementById('payment-total')
        var total = 0
        
        for (let i = 0; i <= nextPaymentMethod; i++) {
            var priceInput = document.getElementById('payment-total-' + i)
            if(priceInput === null || priceInput.value === "")
                continue
            
            var price = parseFloat(priceInput.value)          
            total += price
        }
        paymentTotal.innerHTML = total
    }

    function BuildPaymentMethodRow(){
        var paymentId = String(nextPaymentMethod)

        var paymentMethodCol = GetNewPaymentMethodColumn(paymentId)        
        var coinCol = GetNewCoinColumn(paymentId)
        var bankCol = GetNewBankColumn(paymentId)
        var salePointCol = GetNewSalePointColumn(paymentId)
        var documentNumberCol = GetNewDocumentNumberColumn(paymentId)
        var priceCol = GetNewPaymentPriceColumn(paymentId)
        var totalCol = GetNewPaymentTotalColumn(paymentId)
        var eraseCol = GetNewErasePaymentButtonColumn(paymentId)      

        var row = document.createElement('tr')
        row.classList.add('text-center', 'fs-5')
        row.id = "payment-row-" + paymentId
        row.appendChild(paymentMethodCol)
        row.appendChild(coinCol)
        row.appendChild(bankCol)
        row.appendChild(salePointCol)
        row.appendChild(documentNumberCol)
        row.appendChild(priceCol)
        row.appendChild(totalCol)
        row.appendChild(eraseCol)

        paymentTable.appendChild(row)
    }

    function DeletePaymentRow(id){
        document.getElementById('payment-row-' + id).remove()
        UpdatePaymentTotal()
    }

    function GetNewPaymentMethodColumn(paymentId){
        var methodCol = document.createElement('td')
        methodCol.classList.add('px-0')
        var methodSelect = document.createElement('select')
        var div = document.createElement('div')
        div.classList.add('d-flex', 'justify-content-center', 'm-0', 'w-100', 'p-0')
        methodCol.appendChild(div)
        var option = document.createElement('option')
        option.innerHTML = "&nbsp"
        option.value = ''
        methodSelect.appendChild(option)
        var buffer = "payment-method-" + paymentId
        methodSelect.id = buffer
        methodSelect.name = buffer
        payment_methods.forEach((payment_method) => {
            var option = document.createElement('option')
            option.value = payment_method['id']
            option.innerHTML = payment_method['name']
            methodSelect.appendChild(option)
        })
        methodSelect.classList.add('form-control', 'col-11')
        methodCol.appendChild(methodSelect)
        div.appendChild(methodSelect)
        return methodCol
    }

    function GetNewCoinColumn(paymentId){
        var coinCol = document.createElement('td')
        var coinSelect = document.createElement('select')
        var div = document.createElement('div')
        div.classList.add('d-flex', 'justify-content-center', 'm-0')
        coinCol.appendChild(div)
        var option = document.createElement('option')
        option.innerHTML = "&nbsp"
        option.value = ''
        coinSelect.appendChild(option)
        var buffer = "payment-coin-" + paymentId
        coinSelect.id = buffer
        coinSelect.name = buffer
        coins.forEach((coin) => {
            var option = document.createElement('option')
            option.value = coin['id']
            option.innerHTML = coin['name']
            coinSelect.appendChild(option)
        })
        coinSelect.classList.add('form-control', 'col-11')
        coinSelect.addEventListener('click', function(e) { CoinSelecting(paymentId, e) })
        coinCol.appendChild(coinSelect)
        div.appendChild(coinSelect)
        return coinCol
    }

    function GetNewBankColumn(paymentId){
        var bankCol = document.createElement('td')
        var bankSelect = document.createElement('select')
        //var div = document.createElement('div')
        //div.classList.add('d-flex', 'justify-content-center', 'm-0')
        //bankCol.appendChild(div)
        var option = document.createElement('option')
        option.innerHTML = "&nbsp"
        option.value = ''
        bankSelect.appendChild(option)
        var buffer = "payment-bank-" + paymentId
        bankSelect.id = buffer
        bankSelect.name = buffer
        banks.forEach((bank) => {
            var option = document.createElement('option')
            option.value = bank['id']
            option.innerHTML = bank['name']
            bankSelect.appendChild(option)
        })
        //bankSelect.classList.add('form-control', 'col-11')
        AddClassesToSelect(bankSelect)  
        bankCol.appendChild(bankSelect)
        //div.appendChild(bankSelect)
        return bankCol
    }

    function GetNewSalePointColumn(paymentId){
        var salePointCol = document.createElement('td')
        var salePointSelect = document.createElement('select')
        var div = document.createElement('div')
        div.classList.add('d-flex', 'justify-content-center', 'm-0')
        salePointCol.appendChild(div)
        var option = document.createElement('option')
        option.innerHTML = "&nbsp"
        option.value = ''
        salePointSelect.appendChild(option)
        var buffer = "payment-salepoint-" + paymentId
        salePointSelect.id = buffer
        salePointSelect.name = buffer
        sale_points.forEach((sale_point) => {
            var option = document.createElement('option')
            option.value = sale_point['id']
            option.innerHTML = sale_point['code']
            salePointSelect.appendChild(option)
        })
        salePointSelect.classList.add('form-control', 'col-11')
        salePointCol.appendChild(salePointSelect)
        div.appendChild(salePointSelect)
        return salePointCol
    }

    function GetNewDocumentNumberColumn(paymentId){
        var documentNumberCol = document.createElement('td')
        var documentNumberInput = document.createElement('input')
        var name = "payment-document-" + paymentId
        documentNumberInput.type = 'text'
        documentNumberInput.id = name
        documentNumberInput.name = name
        documentNumberInput.classList.add('form-control')
        documentNumberCol.appendChild(documentNumberInput)
        documentNumberInput.addEventListener('change', function(e){ UpdatePaymentPrice(paymentId); UpdatePaymentTotal(); })
        return documentNumberCol
    }

    function GetNewPaymentPriceColumn(paymentId){
        var priceCol = document.createElement('td')
        var priceInput = document.createElement('input')
        var name = "payment-price-" + paymentId
        priceInput.type = 'text'
        priceInput.id = name
        priceInput.name = name
        priceInput.classList.add('form-control')
        priceCol.appendChild(priceInput)
        priceInput.addEventListener('change', function(e){ UpdatePaymentPrice(paymentId); UpdatePaymentTotal(); })
        return priceCol
    }

    function GetNewPaymentTotalColumn(paymentId){
        var totalCol = document.createElement('td')
        var totalInput = document.createElement('input')
        totalInput.type = 'text'
        totalInput.disabled = true
        totalInput.id = "payment-total-" + paymentId
        totalInput.classList.add('form-control')
        totalCol.appendChild(totalInput)
        return totalCol
    }

    function GetNewErasePaymentButtonColumn(paymentId){
        var eraseCol = document.createElement('td')
        eraseCol.classList.add('text-center')
        var eraseBtn = document.createElement('button')
        eraseBtn.classList.add('btn', 'btn-danger')
        eraseBtn.title = 'Eliminar fila'
        eraseBtn.type = 'button'
        eraseBtn.addEventListener('click', function(){ DeletePaymentRow(paymentId)})
        var eraseIcon = document.createElement('i')
        eraseIcon.classList.add('fa', 'fa-trash')
        eraseBtn.appendChild(eraseIcon)
        eraseCol.appendChild(eraseBtn)
        return eraseCol
    }

</script>
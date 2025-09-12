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
        
        if(coinName !== 'Bolívar' && coinName !== '&nbsp;'){
            EnableIGTF()
        }
        else{
            DisableIGTF()
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
        var igtf = 0
        
        for (let i = 0; i <= nextPaymentMethod; i++) {
            var priceInput = document.getElementById('payment-total-' + i)
            if(priceInput === null || priceInput.value === "")
                continue

            var coinSelect = document.getElementById('payment-coin-' + i)
            var coinName = 'Bolívar'
            for(let i = 0; i < coinSelect.childNodes.length; i++){
                var node = coinSelect.childNodes[i]
                if(node.selected){
                    coinName = node.innerHTML
                }
            }

            var price = parseFloat(priceInput.value)
            

            if(coinName !== 'Bolívar' && coinName !== '&nbsp;'){                
                igtf += price
            }

            total += price
        }

        paymentTotal.innerHTML = total
        UpdateIGTF(igtf)
        UpdatePaymentMethodsDiffWithProducts()
    }

    function UpdatePaymentMethodsDiffWithProducts(){
        const diffElement = document.getElementById('payment-diff')
        const productsTotal = document.getElementById('products-total-bs').innerHTML
        const paymentsTotal = document.getElementById('payment-total').innerHTML
        
        if(productsTotal !== '' && paymentsTotal !== ''){
            var diff = (parseFloat(productsTotal) - parseFloat(paymentsTotal)).toFixed(2)
            diffElement.innerHTML = diff

        }
    }

    function DeletePaymentRow(id){
        document.getElementById('payment-row-' + id).remove()
        UpdatePaymentTotal()
    }

</script>
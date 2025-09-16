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
        var nonVESFound = false

        for(let i = 1; i <= oldId; i++){
            var coinSelect = document.getElementById('payment-coin-' + String(i))
            
            if(coinSelect === null)
                continue

            for(let j = 0; j < coinSelect.childNodes.length; j++){
                var node = coinSelect.childNodes[j]
                if(node.selected){
                    var coinName = node.innerHTML
                    if(coinName !== 'Bolívar' && coinName !== '&nbsp;')
                        nonVESFound = true
                }
            }
                
            if(nonVESFound)
                break
        }
        
        if(nonVESFound)
            EnableIGTF()
        else
            DisableIGTF()

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

        if(priceInput.value < 0)
            priceInput.value = 0

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

        paymentTotal.innerHTML = total.toFixed(2)
        UpdateIGTF(igtf)
        UpdatePaymentMethodsDiffWithProducts()
    }

    function DeletePaymentRow(id){
        document.getElementById('payment-row-' + id).remove()
        UpdatePaymentTotal()
    }
</script>
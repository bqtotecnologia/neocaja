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

    function SalePointSelecting(oldId, e){
        const salePointSelect = document.getElementById('payment-salepoint-' + oldId)

        var targetSalePoint = null

        for(let i = 0; i < sale_points.length; i++){
            if(parseInt(sale_points[i].id) === parseInt(e.target.value)){
                targetSalePoint = sale_points[i]
                break
            }
        }

        if(targetSalePoint !== null)
            $('#payment-bank-' + oldId).val(targetSalePoint.bank).trigger('change')
        else
            $('#payment-bank-' + oldId).val('').trigger('change')
    }

    function PaymentMethodSelecting(oldId, e){       
        // Desbloqueamos los inputs
        payment_method_blockable_fields.forEach((field) => {
            var fieldId = 'payment-' + field + '-' + oldId
            var to_unblock = document.getElementById(fieldId)
            to_unblock.disabled = false

            if(document.getElementById('select2-' + fieldId + '-container') !== null){
                $('#' + fieldId).select2({disabled: false})
            }
        })

        // Buscamos el tipo de método de pago seleccionado
        var methodSelected = null
        for(let i = 0; i < e.target.childNodes.length; i++){
            var node = e.target.childNodes[i]
            if(node.selected){
                methodSelected = node.innerHTML
                break
            }
        }

        // Bloqueamos los inputs correspondientes
        if(Object.keys(payment_method_field_block).includes(methodSelected)){
            var to_block = payment_method_field_block[methodSelected]
            to_block.forEach((block) => {
                fieldId = 'payment-' + block + '-' + oldId
                const input = document.getElementById(fieldId)
                input.disabled = true
                input.value = ''
                if(document.getElementById('select2-' + fieldId + '-container') !== null){
                    $('#' + fieldId).select2({disabled: true})
                    $('#' + fieldId).select2("val", -1)
                }
            })
        }        
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

    function ChangePaymentMethod(position, methodId){
        const input = document.getElementById('payment-method-' + position)
        input.value = methodId
        input.dispatchEvent(new Event('change'))
    }

    function ChangeCoin(position, coinId){
        document.getElementById('payment-coin-' + position).value = coinId
    }

    function ChangeSalePoint(position, salepointId){
        document.getElementById('payment-salepoint-' + position).value = salepointId
    }

    function ChangeBank(position, bankId){
        $('#payment-bank-' + position).val(String(bankId)).trigger('change')
    }

    function ChangeDocumentNumber(position, documentNumber){
        document.getElementById('payment-document-' + position).value = documentNumber
    }

    function ChangePrice(position, price){
        document.getElementById('payment-price-' + position).value = price
    }

    function DeletePaymentRow(id){
        document.getElementById('payment-row-' + id).remove()
        UpdatePaymentTotal()
    }
</script>
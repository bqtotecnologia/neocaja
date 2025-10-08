<script>
    $('#igtf-coin').on('select2:select', async function (e) {
        RefreshIGTF()
    });

    igtf_price.addEventListener('change', function(e) {RefreshIGTF()})

    function RefreshIGTF(){
        var rate = 0
        var coinName = null

        
        for(let i = 0; i < igtf_coin.childNodes.length; i++){
            var node = igtf_coin.childNodes[i]
            if(node.selected){
                coinName = node.innerHTML
            }
        }

        if(coinName !== '&nbsp;')
            rate = coinValues[coinName]

        var price = 0
        
        if(igtf_price.value !== '')            
            price = parseFloat(igtf_price.value)

        var total = parseFloat(price * rate).toFixed(2)
        
        
        igtf_total.value = total
    }

    function IGTFPaymentMethodSelecting(e){       
        // Desbloqueamos los inputs
        payment_method_blockable_fields.forEach((field) => {
            var fieldId = 'igtf-' + field
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
                fieldId = 'igtf-' + block
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

    function DisableIGTF(){
        igtf_table.classList.add('d-none')
        HandleIGTF(true)
    }

    function EnableIGTF(){        
        igtf_table.classList.remove('d-none')
        HandleIGTF(false)
        
    }

    function UpdateIGTF(total){
        var igtf = (total * igtf_rate).toFixed(2)
        var igtfUSD = (igtf / coinValues['Dólar']).toFixed(2)
        igtf_total_label.innerHTML = '3% IGTF: Bs. ' + igtf + ' (' + igtfUSD + '$)'
    }

    function HandleIGTF(disabling){    
        igtf_price.disabled = disabling
        igtf_coin.disabled = disabling
        igtf_method.disabled = disabling
        igtf_bank.disabled = disabling
        igtf_salepoint.disabled = disabling
        igtf_document.disabled = disabling
    }
</script>
<script>
    async function ConfirmProceed(){
        var error = ''
        if(selectedProducts.length === 0){
            error = 'Debe seleccinar al menos un producto para proceder'            
        }

        if(error === '' && false){
            // Comprovamos si seleccionó FOC
            var hasFOC = false
            for(let i = 0; i < selectedProducts.length; i++){
                var product = selectedProducts[i]
                if(product.name === 'FOC'){
                    hasFOC = true
                    break
                }
            }
    
            // Validamos que haya escogido meses consecutivos empezando por el primero
            var nextMonth = parseInt(youngestPayableMonth)
            var consecutiveMonths = 0
            var cyclesMade = -1
            while(true){
                cyclesMade++
                for(let i = 0; i < selectedProducts.length; i++){
                    var product = selectedProducts[i]
                    if(parseInt(product.month) === nextMonth){
                        nextMonth++
                        if(nextMonth === 13)
                            nextMonth = 1
                        
                        consecutiveMonths++
                        break
                    }
                }
    
                if(cyclesMade > selectedProducts.length)
                    break;
            }
    
            if(hasFOC){
                if(selectedProducts.length !== consecutiveMonths + 1)
                    error = 'Debes seleccionar meses consecutivos'
            }
            else{
                if(selectedProducts.length !== consecutiveMonths)
                    error = 'Debes seleccionar meses consecutivos'
            }
        }


        if(error !== ''){
            Swal.fire({
                title: "Acción inválida",
                icon: 'warning',
                html: error,
                confirmButtonText: "Entendido",
            })
        }
        else{
            var confirm = null
            confirm = await WaitForConfirmation()
            if(confirm)
                ProceedToPay()
        }
    }

    async function WaitForConfirmation(){
        var response = await Swal.fire({
            title: "Confirmación",
            html: "¿Desea proceder con los productos selecionados?",
            showCancelButton: true,
            confirmButtonText: "Proceder",
            denyButtonText: 'Cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            }).then((result) => {
            if (result.isConfirmed) {
                return true
            }
            else{
                return false
            }
        });

        return response
    }

    async function ConfirmRegisterPayment(){
        var message = "¿Los datos ingresados son correctos? <br>"
        message += "Cédula: <strong>" + cedulaInput.value + "</strong> <br>"
        message += "Número de referencia: <strong>" + refInput.value + "</strong> <br>"
        message += "Monto: <strong>" + priceInput.value + "</strong> <br>"
        var response = await Swal.fire({
            title: "Confirmación",
            html: message,
            showCancelButton: true,
            confirmButtonText: "Proceder",
            denyButtonText: 'Cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            }).then((result) => {
            if (result.isConfirmed) {
                return true
            }
            else{
                return false
            }
        });

        return response
    }

    function ProceedToPay(){
        proceedToPay = true
        pageBody.scrollIntoView(true, { behavior: "instant", block:'center'});
        productContainer.classList.add('disappear')
        cartContainer.classList.add('disappear')
        proceedContainer.classList.add('disappear')
        checkoutContainer.classList.remove('my-hidden')
        checkoutContainer.classList.add('appear')

        FillCheckoutTable()
    }

    async function FillCheckoutTable(){
        var codes = []
        selectedProducts.forEach((product) => {
            codes.push(product.code)
        })

        var response = await GetRealProducts(codes)
        if(response.status === true){
            selectedProducts = response.data
        }

        var usdTotal = 0
        selectedProducts.forEach((product) => {
            var row = BuildProductRow(product)
            checkoutCartTable.appendChild(row)
            usdTotal += product.price
        })

        document.getElementById('checkout-total-usd').innerHTML = usdTotal
        var vesTotal = GetPrettyCiphers((usdTotal * usdValue).toFixed(2))
        document.getElementById('checkout-total-bs').innerHTML = vesTotal
        totalToPay.innerHTML = vesTotal
    }

    function SelectPaymentMethodType(paymentType){
        ResetPaymentMethodDisplay()

        if(paymentType === '' || !['mobile_payment', 'transfer'].includes(paymentType) )
            return

        FillPaymentMethodSelection(paymentType)
        ShowMethodSelection()
    }

    function FillPaymentMethodSelection(paymentType){
        var targetMethods = paymentMethods[paymentType]
        FillSelectWithThisPaymentMethods(targetMethods)
        ShowPaymentData()                
    }

    function SelectPaymentMethod(paymentId, methodType){
        HideInputs()
        HidePaymentData()

        if(paymentId !== ''){
            DisplayPaymentMethodData(paymentId, methodType)        
            ShowInputs()
            ShowPaymentData()
        }
    }

    function ResetPaymentMethodDisplay(){
        methodSelect.innerHTML = ''
        methodDataTable.innerHTML = ''
        methodSelect.value = ''
        priceInput.value = ''
        refInput.value = ''
        cedulaInput.value = ''

        HidePaymentData()
        HideInputs()
        HideMethodSelection()        
    }

    async function RegisterPayment(){
        var error = ''

        if([cedulaInput.value, refInput.value, priceInput.value, methodSelect.value, methodTypeSelect.value].includes(''))
            error = 'Se requieren de todos los datos para registrar el pago. (Cédula del emisor, número de referencia y monto)'

        if(!availablePaymentMethods.includes(methodTypeSelect.value))
            error = 'Tipo de método de pago inválido'

        if(error === ''){
            var price = priceInput.value
            var to_pay = totalToPay.innerHTML.replace('.', '')
            var to_pay = to_pay.replace(',', '.')
            
            if(price !== to_pay)
                error = 'El monto no coindice con el total a pagar'
        }

        if(error !== ''){
            Swal.fire({
                title: "Acción inválida",
                icon: 'warning',
                html: error,
                confirmButtonText: "Entendido",
            })
        }
        else{
            var confirm = null
            confirm = await ConfirmRegisterPayment()
            if(confirm)
                await TryRegisterPayment()
        }
    }

    async function TryRegisterPayment(){
        var codes = []
        selectedProducts.forEach((product) => {
            codes.push(product.code)
        })

        var data = {
            'cedula': '<?= $_SESSION['neocaja_cedula'] ?>',
            'codes': codes,
            'document': cedulaInput.value,
            'ref': refInput.value,
            'price': priceInput.value,
            'payment_method': methodSelect.value,
            'payment_method_type': methodTypeSelect.value,
        }

        var result = await FetchPayment(data)
        if(result.status === true){
            url = '<?= $base_url ?>/views/panel.php?message=' + result.message
            window.location.href = url
        }
    }
</script>
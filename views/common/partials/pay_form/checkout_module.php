<script>
    async function ConfirmProceed(){
        if(selectedProducts.length === 0){
            Swal.fire({
                title: "Acción inválida",
                icon: 'warning',
                html: "Debe seleccinar al menos un producto para proceder",
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

    function ProceedToPay(){
        proceedToPay = true
        pageBody.scrollIntoView(true, { behavior: "instant", block:'center'});
        productContainer.classList.add('disappear')
        cartContainer.classList.add('disappear')
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
</script>
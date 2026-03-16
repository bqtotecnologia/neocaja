<script>
    function ResetProducts(){
        selectedProducts = []
        productList = []
        cartTable.innerHTML = ''
    }

    function SelectProduct(element){
        if(proceedToPay)
            return

        var code = element.id
        var product = GetProductByCode(code)

        if(ProductInCart(product))
            RemoveProductFromCart(product)
        else
            AddProductToCart(product)

        UpdateTotal()
        
        ToggleProductStyle(element)        
    }

    function AddProductToCart(product){
        selectedProducts.push(product)
        var row = BuildProductRow(product)
        cartTable.appendChild(row)
    }

    function RemoveProductFromCart(productToRemove){
        var newList = []
        selectedProducts.forEach((product) => {
            if(product.code !== productToRemove.code)
                newList.push(product)
        })

        DeleteProductRow(productToRemove)
        selectedProducts = newList
    }

    function UpdateTotal(){
        var usdTotal = 0

        selectedProducts.forEach((product) => {
            usdTotal += product.price
        })

        var vesTotal = (usdTotal * usdValue).toFixed(2)

        totalUSDLabel.innerHTML = GetPrettyCiphers(usdTotal)
        totalVESLabel.innerHTML = GetPrettyCiphers(vesTotal)
    }

    async function CheckUSDRateOfDay(){
        date = dateInput.value
        error = ''
        
        if(date === '')
            error = 'Por favor, escoja la fecha en la que hizo o realizará la transacción.'

        var splits = date.split('-')
        var today = new Date();
        var selectedDate = splits[1] + '-' + splits[2] + '-' + splits[0]
        selectedDate = new Date(selectedDate)
        if(selectedDate > today)
            error = 'La fecha seleccionada debe ser igual o anterior a la fecha actual'

        if(error !== ''){
            Swal.fire({
                title: error,
                icon: 'warning',
                confirmButtonText: "Entendido",
            })

            return
        }

        var fetchResult = await FetchUSDValueOfDay(date)
        if(typeof fetchResult !== "string"){
            fixedRateDate = splits[2] + '/' + splits[1] + '/' + splits[0]
            Swal.fire({
                title: "¿Es esta la fecha correcta?",
                icon:'question',
                html: fixedRateDate,
                showDenyButton: true,
                confirmButtonText: "Sí",
                denyButtonText: "No"
                }).then((result) => {
                if (result.isConfirmed) {
                    rateDate = date                    
                    usdValue = parseFloat(fetchResult['data'])
                    checkoutInputContainer.appendChild(CreateHiddenInput('date', rateDate))
                    ShowProductSelecting()
                }
            });
        }
    }
</script>
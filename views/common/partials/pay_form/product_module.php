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

        totalUSDLabel.innerHTML = usdTotal
        totalVESLabel.innerHTML = GetPrettyCiphers(vesTotal)
    }
</script>
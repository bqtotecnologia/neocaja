<script>
    function GetProductByCode(code){
        var targetProduct = null
        productList.forEach((product) => {
            if(product.code === code)
                targetProduct = product
        })

        return targetProduct
    }

    function ProductInCart(product){
        var inCart = false
        selectedProducts.forEach((cartProduct) => {
            if(cartProduct.code === product.code)
                inCart = true
        })

        return inCart
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
</script>
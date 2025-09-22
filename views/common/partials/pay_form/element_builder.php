<script>
    function ToggleProductStyle(element){
        if(element.classList.contains('product-selected'))
            element.classList.remove('product-selected')
        else
            element.classList.add('product-selected')
    }


    function BuildProductRow(product){
        var productRow = document.createElement('tr')
        productRow.id = 'cart-' + product.code

        var nameCol = BuildProductNameColumn(product.name)
        var priceCol = BuildProductPriceColumn(product.price)
        var total = (product.price * usdValue).toFixed(2)
        var totalCol = BuildProductTotalColumn(total)

        productRow.appendChild(nameCol)
        productRow.appendChild(priceCol)
        productRow.appendChild(totalCol)

        return productRow
    }

    function DeleteProductRow(product){
        const productToDelete = document.getElementById('cart-' + product.code)
        productToDelete.remove()
    }

    function BuildProductNameColumn(name){
        var nameCol = document.createElement('td')
        AddStyleToProductTD(nameCol)
        nameCol.classList.add('text-center')
        nameCol.innerHTML = name
        return nameCol
    }

    function BuildProductPriceColumn(price){
        var priceCol = document.createElement('td')
        AddStyleToProductTD(priceCol)
        priceCol.classList.add('text-right')
        priceCol.innerHTML = price
        return priceCol
    }

    function BuildProductTotalColumn(total){
        var totalCol = document.createElement('td')
        AddStyleToProductTD(totalCol)
        totalCol.innerHTML = GetPrettyCiphers(total)
        return totalCol
    }


    function FillSelectWithThisPaymentMethods(methods){
        methods.forEach((m) => {
            var option = document.createElement('option')
            option.innerHTML = methods.bank
            option.value = methods.id
        })
    }

    function AddStyleToProductTD(td){
        td.classList.add('text-right', 'p-1')
    }
</script>
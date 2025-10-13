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
        methodSelect.innerHTML = ''
        var emptyOption = document.createElement('option')
        emptyOption.innerHTML = 'Escoja una opción'
        methodSelect.appendChild(emptyOption)

        methods.forEach((m) => {
            var option = document.createElement('option')
            option.innerHTML = m.bank
            option.value = m.id
            methodSelect.appendChild(option)
        })
    }

    function DisplayPaymentMethodData(paymentId, methodType){        
        var targetPayment = null
        paymentMethods[methodType].forEach((pm) => {
            if(pm.id === paymentId){
                targetPayment = pm
            }
        })

        const fieldTranslate = {
            'mobile_payment': 'l Pago móvil',
            'transfer': ' la Transferencia'
        }

        methodHeader.innerHTML = 'Datos de' + fieldTranslate[methodType]
        BuildPaymentMethodSelected(targetPayment)                
    }

    function BuildPaymentMethodSelected(payment){
        methodDataTable.innerHTML = ''
        const fieldTranslate = {
            'bank': 'Banco',
            'phone': 'Teléfono',
            'document': 'Cédula/Rif',
            'account_number': 'Número de cuenta'
        }

        for(let field in payment){
            if(!Object.keys(fieldTranslate).includes(field))
                continue

            var row = document.createElement('tr')
            var tdHeader = document.createElement('td')
            var tdValue = document.createElement('td')
            tdHeader.classList.add('fw-bold', 'bg-theme', 'text-white')
            tdValue.classList.add('border', 'border-black')
            tdHeader.innerHTML = fieldTranslate[field]
            tdValue.innerHTML = payment[field]
            row.appendChild(tdHeader)
            row.appendChild(tdValue)
            methodDataTable.appendChild(row)
        }
    }

    function ShowMethodSelection(){
        methodSelectContainer.classList.remove('d-none')
    }

    function HideMethodSelection(){
        methodSelectContainer.classList.add('d-none')
    }

    function ShowPaymentData(){
        methodHeader.classList.remove('d-none')
        methodDataContainer.classList.remove('d-none')
    }

    function HidePaymentData(){
        methodHeader.classList.add('d-none')
        methodDataContainer.classList.add('d-none')
    }

    function ShowInputs(){
        checkoutInputContainer.classList.remove('d-none')
    }

    function HideInputs(){
        checkoutInputContainer.classList.add('d-none')
    }

    function AddStyleToProductTD(td){
        td.classList.add('text-right', 'p-1')
    }
</script>
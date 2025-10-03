<script>     
    ////////////////////////// ACCOUNTS //////////////////////////
    function ShowScholarship(){
        if(scholarshipped){
            scholarshipContainer.classList.remove('d-none')
            scholarshipContainer.innerHTML = 'Beca ' + targetAccount['scholarship'] + ' ' + targetAccount['scholarship_coverage'] + '%'
        }
    }
    
    ////////////////////////// INVOICE //////////////////////////

    function DisplayCoinHistory(coin){
        var text = `<table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tasa</th>
                </tr>
            </thead>
            <tbody>`

        coinHistories[coin].forEach((history) => {
            text += 
                `<tr>
                    <td>` + history['date'] + `</td>
                    <td>` + history['value'] + `</td>
                </tr>`
        })

        text += `</tbody></table>`

        Swal.fire({
            title: '7 tasas más recientes del ' + coin,
            html:text
        })
    } 

    function AddInvoice(month, invoice){
        if(invoice.paid === 1){
            paidMonths.push(month)
        }

        periodMonths.push(month)

        if(invoice.paid === 0 && monthReached === false){
            monthReached = true
            
            lastMonth = GetMonthNumberByName(month)
            if(lastMonth === 1 && month === 'Enero')
                yearOfNextMonth += 1
        }

        var numberDisplay = GetMonthNumberByName(month)
        if(numberDisplay.length === 1)
            numberDisplay = '0' + numberDisplay

        var invoiceMonthCol = document.createElement('td')
        AddBorderToTD(invoiceMonthCol)
        AddInvoiceClassToTD(invoiceMonthCol)
        invoiceMonthCol.innerHTML = numberDisplay + ' ' + month

        // Paid column
        var invoicePaidCol = document.createElement('td')
        AddBorderToTD(invoicePaidCol)
        AddInvoiceClassToTD(invoicePaidCol)
        var paidSymbol = document.createElement('i')
        paidSymbol.classList.add('fa')

        if(invoice.paid === 1){
            paidSymbol.classList.add('fa-check')
            paidSymbol.classList.add('text-success')
        }
        else{
            paidSymbol.classList.add('fa-close')
            paidSymbol.classList.add('text-danger')
        }
        invoicePaidCol.appendChild(paidSymbol)

        // Debt column
        var invoiceDebtCol = document.createElement('td')
        AddBorderToTD(invoiceDebtCol)
        AddInvoiceClassToTD(invoiceDebtCol)
        var debtSymbol = document.createElement('i')
        debtSymbol.classList.add('fa')

        if(invoice.debt === 1){
            debtSymbol.classList.add('fa-check')
            debtSymbol.classList.add('text-success')
        }
        else{
            debtSymbol.classList.add('fa-close')
            debtSymbol.classList.add('text-danger')
        }
        invoiceDebtCol.appendChild(debtSymbol)

        // Partial column
        var invoicePartialCol = document.createElement('td')
        AddBorderToTD(invoicePartialCol)
        AddInvoiceClassToTD(invoicePartialCol)
        var partialSymbol = document.createElement('i')
        partialSymbol.classList.add('fa')

        if(invoice.partial === 1){
            partialSymbol.classList.add('fa-check')
            partialSymbol.classList.add('text-success')
        }
        else{
            partialSymbol.classList.add('fa-close')
            partialSymbol.classList.add('text-danger')
        }
        invoicePartialCol.appendChild(partialSymbol)
        
        var seeCol = document.createElement('td')
        if(invoice.invoice !== undefined){
            seeCol = document.createElement('td')
            AddBorderToTD(seeCol)
            AddInvoiceClassToTD(seeCol)
            var seeLink = document.createElement('a')
            seeLink.classList.add('h6')
            seeLink.href = '<?= $base_url ?>/views/detailers/invoice_details.php?id=' + invoice.invoice
            seeLink.target = '_blank'
            seeLink.innerHTML = 'Ver'
            seeLink.classList.add('fw-bold')
            seeCol.appendChild(seeLink)
        }

        var row = document.createElement('tr')
        row.classList.add('text-center')
        row.classList.add('fs-5')
        row.classList.add('text-black')
        row.appendChild(invoiceMonthCol)
        row.appendChild(invoicePaidCol)
        row.appendChild(invoiceDebtCol)
        row.appendChild(invoicePartialCol)
        row.appendChild(seeCol)
        
        invoiceTable.appendChild(row)
    }

    ////////////////////////// PRODUCTS //////////////////////////

    function BuildProductRow(){
        var productId = String(nextProduct)

        var productCol = GetNewProductColumn(productId)        
        var monthCol = GetNewMonthColumn(productId)
        var basePriceCol = GetNewBasePriceColumn(productId)
        var totalCol = GetNewTotalColumn(productId)
        var eraseCol = GetNewEraseButtonColumn(productId)      

        var row = document.createElement('tr')
        row.classList.add('text-center', 'fs-5')
        row.id = "product-row-" + productId
        row.appendChild(productCol)
        row.appendChild(monthCol)
        row.appendChild(basePriceCol)
        row.appendChild(totalCol)
        row.appendChild(eraseCol)

        productTable.appendChild(row)
    }

    function GetNewProductColumn(productId){
        var productCol = document.createElement('td')
        var productSelect = document.createElement('select')
        var option = document.createElement('option')
        option.innerHTML = "&nbsp"
        option.value = ''
        productSelect.appendChild(option)
        AddSelect2Style(productSelect)        
        var buffer = "product-id-" + productId
        productSelect.id = buffer
        productSelect.name = buffer
        products.forEach((product) => {
            var option = document.createElement('option')
            option.value = product['id']
            option.innerHTML = product['name']
            productSelect.appendChild(option)
        })
        productCol.appendChild(productSelect)
        return productCol
    }

    function GetNewMonthColumn(productId){
        var monthCol = document.createElement('td')
        var div = document.createElement('div')
        div.classList.add('d-flex', 'justify-content-center', 'm-0')
        monthCol.appendChild(div)
        var monthSelect = document.createElement('select')
        var option = document.createElement('option')
        option.innerHTML = "&nbsp"
        option.value = ''
        monthSelect.appendChild(option)
        monthSelect.addEventListener('click', function() { UpdateProductsPrice() })
        monthSelect.classList.add('form-control', 'col-12', 'col-md-8')
        buffer = "product-month-" + productId
        monthSelect.id = buffer
        monthSelect.name = buffer
        
        periodMonths.forEach((month) => {
            if(!paidMonths.includes(month)){
                var option = document.createElement('option')
                option.value = GetMonthNumberByName(month)
                var span = document.createElement('span')
                span.innerHTML = month
                span.classList.add('bg-primary')
                option.appendChild(span)
    
                monthSelect.appendChild(option)
            }
        })

        div.appendChild(monthSelect)
        return monthCol
    }

    function GetNewBasePriceColumn(productId){
        var basePriceCol = document.createElement('td')
        var basePriceInput = document.createElement('input')
        var name = "product-baseprice-" + productId
        basePriceInput.name = name
        basePriceInput.id = name
        basePriceInput.type = 'text'                
        basePriceInput.classList.add('form-control')
        basePriceCol.appendChild(basePriceInput)
        basePriceInput.addEventListener('change', function(e){ UpdateProductsPrice() })
        return basePriceCol
    }

    function GetNewTotalColumn(productId){
        var totalCol = document.createElement('td')
        var totalInput = document.createElement('input')
        totalInput.type = 'text'
        totalInput.disabled = true
        totalInput.id = "product-total-" + productId
        totalInput.classList.add('form-control')
        totalCol.appendChild(totalInput)
        return totalCol
    }

    function GetNewEraseButtonColumn(productId){
        var eraseCol = document.createElement('td')
        eraseCol.classList.add('text-center')
        var eraseBtn = document.createElement('button')
        eraseBtn.classList.add('btn', 'btn-danger')
        eraseBtn.title = 'Eliminar fila'
        eraseBtn.type = 'button'
        eraseBtn.addEventListener('click', function(){ DeleteProductRow(productId)})
        var eraseIcon = document.createElement('i')
        eraseIcon.classList.add('fa', 'fa-trash')
        eraseBtn.appendChild(eraseIcon)
        eraseCol.appendChild(eraseBtn)
        return eraseCol
    }

    ////////////////////////// DEBT TABLE //////////////////////////

    function BuildDebtTable(debtData){
        debtContainer.classList.remove('d-none')
        debtTable.innerHTML = ''

        var monthlyRow = BuildDebtMonthlyRow(debtData.months)
        var retardRow = BuildDebtRetardRow(debtData.retard)
        var focRow = BuildDebtFOCRow(debtData.foc)
        var total = debtData.months + debtData.retard
        if (!debtData.foc)
            total += productPrices['FOC']

        var totalRow = BuildDebtTotalRow(total)

        debtTable.appendChild(monthlyRow)
        debtTable.appendChild(retardRow)
        debtTable.appendChild(focRow)
        debtTable.appendChild(totalRow)
    }

    function BuildDebtMonthlyRow(monnthlyDebt){
        var monthlyRow = document.createElement('tr')
        var monthlyCol = document.createElement('td')
        var monthlyVES = document.createElement('td')
        var monthlyUSD = document.createElement('td')

        monthlyCol.innerHTML = 'Mensualidad'

        AddBorderToTD(monthlyCol)
        AddDebtHeaderStyle(monthlyCol)
        AddBorderToTD(monthlyVES)
        AddBorderToTD(monthlyUSD)

        if(monnthlyDebt > 0){
            monthlyVES.classList.add('text-danger')  
            monthlyUSD.classList.add('text-danger')  
            monthlyVES.innerHTML = 'Bs. ' + GetPrettyCiphers(monnthlyDebt * coinValues['Dólar'])
            monthlyUSD.innerHTML = GetPrettyCiphers(monnthlyDebt) + '$'
        }
        else{
            monthlyVES.classList.add('text-success')  
            monthlyUSD.classList.add('text-success')  
            monthlyVES.innerHTML = 'SIN DEUDA'
            monthlyUSD.innerHTML = 'SIN DEUDA'
        }
        monthlyRow.appendChild(monthlyCol)
        monthlyRow.appendChild(monthlyVES)
        monthlyRow.appendChild(monthlyUSD)

        return monthlyRow
    }

    function BuildDebtRetardRow(retardDebt){
        var retardRow = document.createElement('tr')
        var retardCol = document.createElement('td')
        var retardVES = document.createElement('td')
        var retardUSD = document.createElement('td')
        AddBorderToTD(retardCol)
        AddDebtHeaderStyle(retardCol)
        AddBorderToTD(retardVES)
        AddBorderToTD(retardUSD)
        retardCol.innerHTML = 'Mora'

        if(retardDebt > 0){
            if(scholarshipped){
                if(scholarship_with_retard){
                    retardVES.classList.add('text-danger')  
                    retardUSD.classList.add('text-danger')  
                    retardVES.innerHTML = 'Bs. ' + GetPrettyCiphers(retardDebt * coinValues['Dólar'])
                    retardUSD.innerHTML = GetPrettyCiphers(retardDebt) + '$'
                }
                else{
                    retardVES.classList.add('text-success')  
                retardUSD.classList.add('text-success') 
                    retardVES.innerHTML = 'SIN MORA'
                    retardUSD.innerHTML = 'SIN MORA'
                }
            }
            else{
                retardVES.classList.add('text-danger')  
                retardUSD.classList.add('text-danger')  
                retardVES.innerHTML = 'Bs. ' + GetPrettyCiphers(retardDebt * coinValues['Dólar'])
                retardUSD.innerHTML = GetPrettyCiphers(retardDebt) + '$'
            }
        }
        else{
            retardVES.classList.add('text-success')  
            retardUSD.classList.add('text-success') 
            retardVES.innerHTML = 'SIN MORA'
            retardUSD.innerHTML = 'SIN MORA'
        }

        retardRow.appendChild(retardCol)
        retardRow.appendChild(retardVES)
        retardRow.appendChild(retardUSD)

        
        

        return retardRow
    }

    function BuildDebtFOCRow(focDebt){
        var focRow = document.createElement('tr')            
        var focCol = document.createElement('td')
        var focVES = document.createElement('td')
        var focUSD = document.createElement('td')
        AddBorderToTD(focCol)
        AddDebtHeaderStyle(focCol)
        AddBorderToTD(focVES)
        AddBorderToTD(focUSD)
        focCol.innerHTML = 'FOC'

        focRow.appendChild(focCol)
        if(focDebt === true){
            focVES.classList.add('text-success')  
            focVES.colSpan = 2
            focVES.innerHTML = 'PAGADO'            
            focRow.appendChild(focVES)
        }
        else{
            focVES.classList.add('text-danger')  
            focUSD.classList.add('text-danger')
            
            focVES.innerHTML = 'Bs. ' + GetPrettyCiphers(productPrices['FOC'] * coinValues['Dólar'])
            focUSD.innerHTML = productPrices['FOC'] + '$'
            focRow.appendChild(focVES)
            focRow.appendChild(focUSD)
        }        

        return focRow
    }

    function BuildDebtTotalRow(totalUSD){
        var totalRow = document.createElement('tr')
        var totalCol = document.createElement('td')
        var vesCol = document.createElement('td')
        var usdCol = document.createElement('td')

        totalCol.innerHTML = 'TOTAL'
        AddBorderToTD(totalCol)
        AddDebtHeaderStyle(totalCol)
        AddBorderToTD(vesCol)
        AddBorderToTD(usdCol)

        totalRow.appendChild(totalCol)
        if(totalUSD > 0){
            vesCol.classList.add('text-danger', 'fw-bold')
            usdCol.classList.add('text-danger', 'fw-bold')
            vesCol.innerHTML = 'Bs. ' + GetPrettyCiphers(totalUSD * coinValues['Dólar'])
            usdCol.innerHTML = totalUSD + '$'
            totalRow.appendChild(vesCol)
            totalRow.appendChild(usdCol)
        }
        else{
            vesCol.classList.add('text-success')
            vesCol.innerHTML = 'SIN DEUDA'
            vesCol.colSpan = 2
            totalRow.appendChild(vesCol)
        }
        
        return totalRow
    }


    ////////////////////////// PAYMENT METHODS //////////////////////////
    function BuildPaymentMethodRow(){
        var paymentId = String(nextPaymentMethod)

        var paymentMethodCol = GetNewPaymentMethodColumn(paymentId)        
        var coinCol = GetNewCoinColumn(paymentId)
        var bankCol = GetNewBankColumn(paymentId)
        var salePointCol = GetNewSalePointColumn(paymentId)
        var documentNumberCol = GetNewDocumentNumberColumn(paymentId)
        var priceCol = GetNewPaymentPriceColumn(paymentId)
        var totalCol = GetNewPaymentTotalColumn(paymentId)
        var eraseCol = GetNewErasePaymentButtonColumn(paymentId)      

        var row = document.createElement('tr')
        row.classList.add('text-center', 'fs-5')
        row.id = "payment-row-" + paymentId
        row.appendChild(paymentMethodCol)
        row.appendChild(coinCol)
        row.appendChild(bankCol)
        row.appendChild(salePointCol)
        row.appendChild(documentNumberCol)
        row.appendChild(priceCol)
        row.appendChild(totalCol)
        row.appendChild(eraseCol)

        paymentTable.appendChild(row)
    }

    function GetNewPaymentMethodColumn(paymentId){
        var methodCol = document.createElement('td')
        methodCol.classList.add('px-0')
        var methodSelect = document.createElement('select')
        var div = document.createElement('div')
        div.classList.add('d-flex', 'justify-content-center', 'm-0', 'w-100', 'p-0')
        methodCol.appendChild(div)
        var option = document.createElement('option')
        option.innerHTML = "&nbsp"
        option.value = ''
        methodSelect.appendChild(option)
        var buffer = "payment-method-" + paymentId
        methodSelect.id = buffer
        methodSelect.name = buffer
        payment_methods.forEach((payment_method) => {
            var option = document.createElement('option')
            option.value = payment_method['id']
            option.innerHTML = payment_method['name']
            methodSelect.appendChild(option)
        })
        methodSelect.classList.add('form-control', 'col-11')
        methodCol.appendChild(methodSelect)
        div.appendChild(methodSelect)
        return methodCol
    }

    function GetNewCoinColumn(paymentId){
        var coinCol = document.createElement('td')
        var coinSelect = document.createElement('select')
        var div = document.createElement('div')
        div.classList.add('d-flex', 'justify-content-center', 'm-0')
        coinCol.appendChild(div)
        var option = document.createElement('option')
        option.innerHTML = "&nbsp"
        option.value = ''
        coinSelect.appendChild(option)
        var buffer = "payment-coin-" + paymentId
        coinSelect.id = buffer
        coinSelect.name = buffer
        coins.forEach((coin) => {
            var option = document.createElement('option')
            option.value = coin['id']
            option.innerHTML = coin['name']
            coinSelect.appendChild(option)
        })
        coinSelect.classList.add('form-control', 'col-11')
        coinSelect.addEventListener('click', function(e) { CoinSelecting(paymentId, e) })
        coinCol.appendChild(coinSelect)
        div.appendChild(coinSelect)
        return coinCol
    }

    function GetNewBankColumn(paymentId){
        var bankCol = document.createElement('td')
        var bankSelect = document.createElement('select')
        var option = document.createElement('option')
        option.innerHTML = "&nbsp"
        option.value = ''
        bankSelect.appendChild(option)
        var buffer = "payment-bank-" + paymentId
        bankSelect.id = buffer
        bankSelect.name = buffer
        banks.forEach((bank) => {
            var option = document.createElement('option')
            option.value = bank['id']
            option.innerHTML = bank['name']
            bankSelect.appendChild(option)
        })
        AddSelect2Style(bankSelect)  
        bankCol.appendChild(bankSelect)
        return bankCol
    }

    function GetNewSalePointColumn(paymentId){
        var salePointCol = document.createElement('td')
        var salePointSelect = document.createElement('select')
        var div = document.createElement('div')
        div.classList.add('d-flex', 'justify-content-center', 'm-0')
        salePointCol.appendChild(div)
        var option = document.createElement('option')
        option.innerHTML = "&nbsp"
        option.value = ''
        salePointSelect.appendChild(option)
        var buffer = "payment-salepoint-" + paymentId
        salePointSelect.id = buffer
        salePointSelect.name = buffer
        sale_points.forEach((sale_point) => {
            var option = document.createElement('option')
            option.value = sale_point['id']
            option.innerHTML = sale_point['code']
            salePointSelect.appendChild(option)
        })
        salePointSelect.classList.add('form-control', 'col-11')
        salePointCol.appendChild(salePointSelect)
        div.appendChild(salePointSelect)
        return salePointCol
    }

    function GetNewDocumentNumberColumn(paymentId){
        var documentNumberCol = document.createElement('td')
        var documentNumberInput = document.createElement('input')
        var name = "payment-document-" + paymentId
        documentNumberInput.type = 'text'
        documentNumberInput.id = name
        documentNumberInput.name = name
        documentNumberInput.classList.add('form-control')
        documentNumberCol.appendChild(documentNumberInput)
        documentNumberInput.addEventListener('change', function(e){ UpdatePaymentPrice(paymentId); UpdatePaymentTotal(); })
        return documentNumberCol
    }

    function GetNewPaymentPriceColumn(paymentId){
        var priceCol = document.createElement('td')
        var priceInput = document.createElement('input')
        var name = "payment-price-" + paymentId
        priceInput.type = 'text'
        priceInput.id = name
        priceInput.name = name
        priceInput.classList.add('form-control')
        priceCol.appendChild(priceInput)
        priceInput.addEventListener('change', function(e){ UpdatePaymentPrice(paymentId); UpdatePaymentTotal(); })
        return priceCol
    }

    function GetNewPaymentTotalColumn(paymentId){
        var totalCol = document.createElement('td')
        var totalInput = document.createElement('input')
        totalInput.type = 'text'
        totalInput.disabled = true
        totalInput.id = "payment-total-" + paymentId
        totalInput.classList.add('form-control')
        totalCol.appendChild(totalInput)
        return totalCol
    }

    function GetNewErasePaymentButtonColumn(paymentId){
        var eraseCol = document.createElement('td')
        eraseCol.classList.add('text-center')
        var eraseBtn = document.createElement('button')
        eraseBtn.classList.add('btn', 'btn-danger')
        eraseBtn.title = 'Eliminar fila'
        eraseBtn.type = 'button'
        eraseBtn.addEventListener('click', function(){ DeletePaymentRow(paymentId)})
        var eraseIcon = document.createElement('i')
        eraseIcon.classList.add('fa', 'fa-trash')
        eraseBtn.appendChild(eraseIcon)
        eraseCol.appendChild(eraseBtn)
        return eraseCol
    }

    function UpdatePaymentMethodsDiffWithProducts(){
        const diffElement = document.getElementById('payment-diff')
        const productsTotal = document.getElementById('products-total-bs').innerHTML
        const paymentsTotal = document.getElementById('payment-total').innerHTML
        
        if(productsTotal !== '' && paymentsTotal !== ''){
            var diff = (parseFloat(productsTotal) - parseFloat(paymentsTotal)).toFixed(2)
            diffElement.innerHTML = diff

        }
    }
    

    ////////////////////////// STYLE FUNCTIONS //////////////////////////
    function AddInvoiceClassToTD(td){
        td.classList.add('bg-white', 'text-black')
    }

    function AddBorderToTD(td){
        td.classList.add('p-1', 'border', 'border-black')
    }

    function AddDebtHeaderStyle(td){
        td.classList.add('bg-theme', 'text-white', 'fw-bold')
    }

    function AddSelect2Style(select){
        select.classList.add('form-control', 'col-12', 'col-md-8', 'select2')
    }
</script>
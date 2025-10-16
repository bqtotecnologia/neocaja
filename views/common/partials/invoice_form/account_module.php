<script>    
    async function AccountSelecting(id){
        if(id === '')
            return

        ClearInvoices()
        CleanProducts()
        var error = false
        targetAccount = await GetAccountData(id)
        if(targetAccount.status !== false){
            targetAccount = targetAccount.data
            scholarshipped = !(targetAccount['scholarship_coverage'] === null && targetAccount['scholarship_coverage'] === null)
        }
        else
            error = true

        if(!error){
            var accountButton = document.getElementById('account-link')
            accountButton.classList.add('d-none')
            debtContainer.classList.add('d-none')
            accountButton.classList.remove('d-none')
            accountButton.href  = '<?= $base_url ?>' + '/views/detailers/account_details.php?id=' + targetAccount.id

            var accountMonths = await GetAccountState(id, '<?= $periodId ?>')
            invoiceTable.innerHTML = ''
            await DisplayDebt(id, '<?= $periodId ?>')
            await DisplayInvoices(accountMonths.data)

            ShowScholarship()
            //AddProduct()
            
            if(incomesInput.value === '')
                DisplayDefaultProduct()

            UpdateProductsPrice()
        }
    }    

    async function PaymentSelecting(select){
        target_payment = ''
        var found = await GetAccountPayment(select.value)
        if(found.status === false)
            return

        target_payment = found.data
        $('#account').val(target_payment.payment.account_id).trigger('change')

        rateDate.value = target_payment.payment.date
        rateDate.dispatchEvent(new Event('change'))
        await new Promise(r => setTimeout(r, 500))
        
        // Colocando los productos de la compra
        target_payment.products.forEach((product) => {
            AddProduct()
            var productName = product.product
            var productPrice = product.price
            var monthNumber = ''
            if(product.product.includes('Mensualidad')){
                var month = ''
                for(let key in monthNumberToName){
                    var currentMonth = monthNumberToName[key]
                    if(product.product.includes(currentMonth)){
                        month = currentMonth
                    }
                }
                var monthNumber = GetMonthNumberByName(month)
                
                if(product.product.includes('con mora')){
                    ChangeProduct(nextProduct - 1, productIds['Diferencia Mensualidad']) 
                    ChangeMonth(nextProduct - 1, monthNumber)
                    ChangeProductPrice(nextProduct - 1, debtData.data.retard.detail[month])
                    AddProduct()

                    if(product.product.includes('Restante'))
                        productName = 'Saldo Mensualidad'
                        else
                    productName = 'Mensualidad'
                }
                else if(product.product.includes('Restante')){
                    ChangeProduct(nextProduct - 1, productIds['Saldo Mensualidad']) 
                    ChangeMonth(nextProduct - 1, monthNumber)
                    ChangeProductPrice(nextProduct - 1, debtData.data.months.detail[month])
                    AddProduct()
                }
                else if(product.product === 'Mensualidad ' + month){
                    productName = 'Mensualidad'
                }
                
                productPrice = debtData.data.months.detail[month]
                if(productPrice === undefined){
                    // Es el precio normal de la mensualidad
                    productPrice = productPrices['Mensualidad']
                    if(scholarshipped)
                        productPrice = productPrice - (productPrice * (targetAccount.scholarship_coverage / 100))
                }
                
            }                

            if(monthNumber !== '')
                ChangeMonth(nextProduct - 1, monthNumber)

            ChangeProduct(nextProduct - 1, productIds[productName])
            ChangeProductPrice(nextProduct - 1, productPrice)
        })

        UpdateProductsPrice()
        

        // Colocando el método de pago
        var target_payment_method = ''
        if(target_payment.payment.payment_method_type === 'mobile_payment')
            target_payment_method = 'Pago móvil'
        else if(target_payment.payment.payment_method_type === 'transfer')
            target_payment_method = 'Transferencia'

        payment_methods.forEach((method) => {
            if(method.name === target_payment_method)
                target_payment_method = method.id
        })

        
        ChangePaymentMethod(nextPaymentMethod - 1, target_payment_method)

        // Colocando la moneda
        var target_coin = 'Bolívar'

        coins.forEach((coin) => {
            if(coin.name === target_coin)
                target_coin = coin.id
        })

        ChangeCoin(nextPaymentMethod - 1, target_coin)

        ChangeSalePoint(nextPaymentMethod - 1, '')
        
        ChangeBank(nextPaymentMethod - 1, target_payment.payment_method.bank_id)
        
        ChangeDocumentNumber(nextPaymentMethod - 1, target_payment.payment.ref)
        ChangePrice(nextPaymentMethod - 1, target_payment.payment.price)
        UpdatePaymentPrice(nextPaymentMethod - 1)
        UpdatePaymentTotal()
    }

</script>
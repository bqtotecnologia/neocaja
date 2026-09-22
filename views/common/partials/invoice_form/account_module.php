<script>    
    async function AccountSelecting(id){
        if(id === '')
            return

        accountButton.classList.add('d-none')
        ToggleLoadingIcon()
        ClearInvoices()
        CleanPeriodButtons()
        CleanProducts(true)
        var error = false
        targetAccount = await GetAccountData(id)
        if(targetAccount.status !== false){
            targetAccount = targetAccount.data
            scholarshipped = !(targetAccount['scholarship_coverage'] === null && targetAccount['scholarship_coverage'] === null)
        }
        else
            error = true

        if(!error){
            chosenPeriod = ''
            availablePeriods = []
            
            debtContainer.classList.add('d-none')
            invoiceContainer.classList.add('d-none')           

            accountStates = await GetAccountState(id)            
            debtData = await GetDebtOfAccount(id)

            for(let period in accountStates.data){
                availablePeriods.push(period)
            }

            var lastPeriod = ''
            if(availablePeriods.length > 0){
                lastPeriod = availablePeriods[0]
            }

            var oldestDebt = false
            for(let period in debtData.data){
                var current = debtData.data[period]
                if(current.foc > 0 || current.months > 0 || current.retard > 0)
                    oldestDebt = period
            }

            
            
            studentIsRetard = (oldestDebt !== lastPeriod) && (oldestDebt !== false)
            // Está moroso en un periodo anterior

            if(studentIsRetard)
                chosenPeriod = oldestDebt            
            else
                chosenPeriod = lastPeriod            
            
            if(incomesInput.value === '')
                await DisplayDefaultProduct()

            UpdateProductsPrice()
        }

        accountButton.classList.remove('d-none')
        accountButton.href  = '<?= $base_url ?>' + '/views/detailers/account_details.php?id=' + targetAccount.id

        DisplayPeriods(availablePeriods)
        DisplayDebt(chosenPeriod)
        DisplayInvoices(chosenPeriod)
        ChangeSelectedPeriod(chosenPeriod)           
        ShowScholarship()
        ShowCompany()

        ToggleLoadingIcon()

        if(studentIsRetard){
            Swal.fire({
                icon: 'warning',
                title: 'El estudiante tiene deuda de un periodo anterior',
                html: 'Posee deuda en uno o más periodos, siendo el primero de esos el <strong>' + chosenPeriod + '</strong>'
            })
        }
    }    

    async function PaymentSelecting(select){
        target_payment = ''
        var found = await GetAccountPayment(select.value)
        if(found.status === false)
            return

        target_payment = found.data
        $('#account').val(target_payment.payment.account_id).trigger('change')

        var remotePaymentLink = document.getElementById('remote-payment-link')
        debtContainer.classList.add('d-none')
        remotePaymentLink.classList.remove('d-none')
        remotePaymentLink.href  = '<?= $base_url ?>' + '/views/forms/update_remote_payment.php?id=' + target_payment.payment.id

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
                    ChangeProductPrice(nextProduct - 1, debtData.data[chosenPeriod].retard.detail[month])
                    AddProduct()

                    if(product.product.includes('Restante'))
                        productName = 'Saldo Mensualidad'
                    else
                        productName = 'Mensualidad'
                }
                else if(product.product.includes('Restante')){
                    ChangeProduct(nextProduct - 1, productIds['Saldo Mensualidad']) 
                    ChangeMonth(nextProduct - 1, monthNumber)
                    ChangeProductPrice(nextProduct - 1, debtData.data[chosenPeriod].months.detail[month])
                    AddProduct()
                }
                else if(product.product === 'Mensualidad ' + month){
                    productName = 'Mensualidad'
                }
                
                productPrice = debtData.data[chosenPeriod].months.detail[month]
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
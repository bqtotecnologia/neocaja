<script>
    function AddProduct(){
        BuildProductRow()
        $(".select2").select2({width:'100%'});

        var oldId = nextProduct
        $('#product-id-' + String(nextProduct)).on('select2:select', async function (e) {
            ProductSelecting(oldId, e)
        })

        nextProduct++
    }

    function ProductSelecting(oldId, e){                
        var price = 0
        var productName = null

        for(let i = 0; i < e.target.childNodes.length; i++){
            // searching the product name to get it's price
            var node = e.target.childNodes[i]
            if(node.selected){
                productName = node.innerHTML
            }
        }

        var totalUsd = 0

        if(productName !== '&nbsp;')
            totalUsd = parseFloat(productPrices[productName])       

        if(scholarshipped){
            if(productName === 'Mensualidad'){
                var  discount = parseFloat(totalUsd) * (parseFloat(targetAccount['scholarship_coverage']) / 100)
                totalUsd = totalUsd - discount
            }
        }

        var priceInput = document.getElementById('product-baseprice-' + String(oldId))
        priceInput.value = totalUsd
        
        var productTotalInput = document.getElementById('product-total-' + String(oldId))
        var totalVes = totalUsd * coinValues['Dólar']
        productTotalInput.value = totalVes    
        UpdateProductsPrice()
    }

    function UpdateProductsPrice(forceUpdatePrice = false){
        for(let i = 0; i <= nextProduct; i++){
            var productBasePriceInput = document.getElementById('product-baseprice-' + String(i))            

            if(productBasePriceInput === null)
                continue

            var productSelect = document.getElementById('product-id-' + String(i))
            var productName = productSelect.options[productSelect.selectedIndex].innerHTML
            if(productName === '&nbsp;' || productName === '')
                continue

            var productBasePrice = productPrices[productName]            

            if(!forceUpdatePrice)
                productBasePrice = parseFloat(productBasePriceInput.value)

            var monthInput = document.getElementById('product-month-' + i)
            var targetOption = monthInput.options[monthInput.selectedIndex]
            
            if(productName === 'Diferencia Mensualidad' && forceUpdatePrice){
                // Añade la mora
                var monthlyPrice = productPrices['Mensualidad']
                productBasePrice = monthlyPrice * (retardPercent / 100)
            }

            if(forceUpdatePrice)
                productBasePriceInput.value = productBasePrice

            if(productBasePrice < 0){
                productBasePriceInput.value = 0
                productBasePrice = 0
            }

            var productTotal = productBasePrice * coinValues['Dólar']
            document.getElementById('product-total-' + String(i)).value = productTotal.toFixed(2)       
        }
        
        UpdateProductTotal()
    }


    function UpdateProductTotal(){
        var productsTotal = document.getElementById('products-total')
        var productsTotalBs = document.getElementById('products-total-bs')
        var usdRate = coinValues['Dólar']
        var total = 0
        
        // adding all totals of products
        for (let i = 0; i <= nextProduct; i++) {
            var priceInput = document.getElementById('product-baseprice-' + i)
            if(priceInput === null || priceInput.value === "")
                continue
            
            var price = parseFloat(priceInput.value)          
            total += price
        }

        productsTotal.innerHTML = '$ ' + total.toFixed(2)
        productsTotalBs.innerHTML = 'Bs. ' + (total * parseFloat(usdRate)).toFixed(2)
        UpdatePaymentMethodsDiffWithProducts()
    }

    function DisplayDefaultProduct(){      
        var prcice = 0
        if(debtData.data.foc === false){
            AddProduct()
            price = productPrices['FOC']
            ChangeProduct(nextProduct - 1, productIds['FOC'])
            ChangeProductPrice(nextProduct - 1, price)
        }

        periodMonths.forEach((pmonth) => {
            if(!paidMonths.includes(pmonth)){
                price = productPrices['Mensualidad']

                if(scholarshipped){
                    price = price - (price * (targetAccount['scholarship_coverage'] / 100))
                }
                var monthNumber = GetMonthNumberByName(pmonth)

                if(Object.keys(debtData.data.months.detail).includes(pmonth)){
                    price = debtData.data.months.detail[pmonth]
                }

                if(Object.keys(debtData.data.retard.detail).includes(pmonth)){
                    var retardPrice = debtData.data.retard.detail[pmonth]
                    AddProduct()
                    ChangeMonth(nextProduct - 1, monthNumber)
                    ChangeProduct(nextProduct - 1, productIds['Diferencia Mensualidad'])    
                    ChangeProductPrice(nextProduct - 1, retardPrice)
                }

                AddProduct()
                ChangeMonth(nextProduct - 1, monthNumber)
                if(partialMonths.includes(pmonth)){
                    ChangeProduct(nextProduct - 1, productIds['Saldo Mensualidad'])                    
                }
                else                
                    ChangeProduct(nextProduct - 1, productIds['Mensualidad'])
                

                ChangeProductPrice(nextProduct - 1, price)
            }
        })        
        //UpdateProductsPrice(true)
    }

    function ChangeMonth(position, month){      
        $('#product-month-' + position).val(String(month)) 
    }

    function ChangeProduct(position, productId){
        $('#product-id-' + position).select2("val", String(productId))
    }

    function ChangeProductPrice(position, price){
        const input = document.getElementById('product-baseprice-' + position)
        input.value = price
    }

    function CleanProducts(cleanPeriodMonths = false){
        lastMonth = currentMonth
        monthReached = false
        productTable.innerHTML = ''
        nextProduct = 1
        paidMonths = []
        partialMonths = []        
        youngestPayableMonth = null
        updatePricesAccordToDebt = false
        scholarshipContainer.innerHTML = ''
        scholarshipContainer.classList.add('d-none')
        companyContainer.innerHTML = ''
        companyContainer.classList.add('d-none')

        if(cleanPeriodMonths === true)
            periodMonths = []
    }

    function DeleteProductRow(id){
        document.getElementById('product-row-' + id).remove()
        UpdateProductTotal()
    }
</script>
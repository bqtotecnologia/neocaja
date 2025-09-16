<script>
    function UpdateDefaultProducts(debt){
        var firstProduct = document.getElementById('product-baseprice-1')
        var secondProduct = document.getElementById('product-baseprice-2')
        if(debt.months > 0 && debt.retard > 0 && !scholarshipped){
            // Tiene tanto mora como deuda
            firstProduct.value = debt.retard
            secondProduct.value = debt.months
        }
        else if(debt.months > 0){
            // Aún debe el mes pero no tiene mora
            firstProduct.value = debt.months
        }
    }

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

        var total = 0

        if(productName === '&nbsp;')
            price = 0
        else
            price = productPrices[productName]

        var priceInput = document.getElementById('product-baseprice-' + String(oldId))
        priceInput.value = price
        total = price
        
        if(scholarshipped){
            if(productName === 'Mensualidad')
                total = parseFloat(price) * (parseFloat(targetAccount['scholarship_coverage']) / 100)
        }
        
        var productTotalInput = document.getElementById('product-total-' + String(oldId))
        productTotalInput.value = total    
        UpdateProductsPrice()
    }

    function UpdateProductsPrice(forceUpdatePrice = false){
        UpdateScholarshipValues()

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

            var productScholarship = document.getElementById('product-scholarship-' + String(i))
            if(productName !== 'Mensualidad'){
                productScholarship.value = '0%'
            }
            var discount = parseFloat(productScholarship.value.trim('%'))

            var productTotal = (productBasePrice - (productBasePrice * (discount / 100))).toFixed(2)
            document.getElementById('product-total-' + String(i)).value = productTotal          
        }
        
        UpdateProductTotal()
    }

    function UpdateScholarshipValues(){
        var scholarshipValue = '0%'
        var scholarships = document.getElementsByClassName('scholarship-input')
        if(targetAccount['scholarship_coverage'] !== null && targetAccount['scholarship_coverage'] !== undefined )
            scholarshipValue = String(targetAccount['scholarship_coverage']) + '%'

        for (let i = 0; i < scholarships.length; i++) {      
            var targetScholarship = scholarships[i]
            var targetId = targetScholarship.id.split('-')
            targetId = targetId[targetId.length - 1]
            var productSelect = document.getElementById('product-id-' + targetId)
            var productName = productSelect.options[productSelect.selectedIndex].innerHTML
            if(productName === 'Mensualidad')            
                scholarships[i].value = scholarshipValue
            else
                scholarships[i].value = '0%'
        }
    }

    function UpdateProductTotal(){
        var productsTotal = document.getElementById('products-total')
        var productsTotalBs = document.getElementById('products-total-bs')
        var usdRate = document.getElementById('Dólar-rate').innerHTML
        var total = 0
        
        // adding all totals of products
        for (let i = 0; i <= nextProduct; i++) {
            var priceInput = document.getElementById('product-total-' + i)
            if(priceInput === null || priceInput.value === "")
                continue
            
            var price = parseFloat(priceInput.value)          
            total += price
        }

        productsTotal.innerHTML = total + '$'
        productsTotalBs.innerHTML = (total * parseFloat(usdRate)).toFixed(2)
        UpdatePaymentMethodsDiffWithProducts()
    }

    function DisplayDefaultProduct(){      
        if(nextProduct !== 2)
            return

        var nextMonth = parseInt(lastMonth)
        
        if(GetMonthIsRetarded(nextMonth)){
            // Se le aplica mora
            if(targetAccount['scholarship_coverage'] === null && targetAccount['scholarship_coverage'] === undefined){
                // No se le aplica mora porque es becado
                ChangeMonth(nextMonth, nextProduct - 1)
                ChangeProduct(nextProduct - 1, productIds['Diferencia Mensualidad'])
                AddProduct()
            }
        }

        ChangeMonth(nextMonth, nextProduct - 1)
        ChangeProduct(nextProduct - 1, productIds['Mensualidad'])        

        UpdateProductsPrice(true)
    }

    function ChangeMonth(month, id){      
        $('#product-month-' + (nextProduct - 1)).val(String(month)) 
    }

    function ChangeProduct(position, productId){
        $('#product-id-' + position).select2("val", String(productId))
    }

    function CleanProducts(){
        lastMonth = currentMonth
        monthReached = false
        productTable.innerHTML = ''
        nextProduct = 1
        paidMonths = []
        updatePricesAccordToDebt = false
    }

    function DeleteProductRow(id){
        document.getElementById('product-row-' + id).remove()
        UpdateProductTotal()
    }
</script>
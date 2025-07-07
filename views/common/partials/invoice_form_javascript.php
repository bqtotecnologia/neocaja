<script>
    const invoiceContainer = document.getElementById('invoices')
    const invoiceTable = document.getElementById('invoice-table')
    const productTable = document.getElementById('product-table')

    const products = []
    const productPrices = {}
    const payment_methods = []
    const coins = []
    const sale_points = []
    const coinHistories = {}

    const months = {
        '1': 'Enero',
        '2': 'Febrero',
        '3': 'Marzo',
        '4': 'Abril',
        '5': 'Mayo',
        '6': 'Junio',
        '7': 'Julio',
        '8': 'Agosto',
        '9': 'Septiembre',
        '10': 'Octubre',
        '11': 'Noviembre',
        '12': 'Diciembre',
    }    

    let nextProduct = 1
    let nextPaymentMethod = 1
    let targetAccount = {}
</script>

<?php foreach($products as $product) { ?>
    <script>
        product = {
            'name': '<?= $product['name'] ?>',
            'id': '<?= $product['id'] ?>',
        }
        products.push(product)

        productPrices['<?= $product['name'] ?>'] = parseFloat('<?= $product['price'] ?>')
    </script>
<?php } ?>

<?php foreach($coinHistories as $coin => $history) { if($coin === 'Bolívar') continue; ?>
    <script>
        coinHistories['<?= $coin ?>'] = []        
    </script>

    <?php foreach($history as $point) { ?>
        <script>
            var to_add = {
                'date': '<?= date('d/m/Y', strtotime($point['created_at'])) ?>',
                'value': '<?= $point['price'] ?>'
            }
            coinHistories['<?= $coin ?>'].push(to_add)
        </script>
    <?php } ?>

<?php } ?>


<script>  
    AddProduct()    

    $('#account').on('select2:select', async function (e) {
        ClearInvoices()
        if(e.target.value !== ''){
            var result = await GetInvoicesOfAccount(e.target.value)            
            
            if(typeof result !== "string"){
                targetAccount = await GetAccountData(e.target.value)
                if(result.data.length > 0){
                    invoiceContainer.classList.remove('d-none')
                    targetAccount = targetAccount.data
                    result.data.forEach((invoice) => {
                        AddInvoice(invoice)
                    })
                }
            }

            var scholarships = document.getElementsByClassName('scholarship-input')
            if(targetAccount['scholarship_coverage'] === null)
                scholarshipValue = '0%'
            else
                scholarshipValue = String(targetAccount) + '%'

            for (let i = 0; i < scholarships.length; i++) {               
                scholarships[i].value = scholarshipValue
            }

        }
    });

    async function GetInvoicesOfAccount(account){
        var period = '<?= $periodId ?>'
        var url = '<?= $base_url ?>/api/get_invoices_of_account.php?account=' + account + '&period=' + period

        var fetchConfig = {
            method: 'GET', 
            headers: {
                'Content-Type': 'application/json',
            },
        }

        return await TryFetch(url, fetchConfig)
    }

    async function GetAccountData(account){
        var url = '<?= $base_url ?>/api/get_account_data.php?account=' + account

        var fetchConfig = {
            method: 'GET', 
            headers: {
                'Content-Type': 'application/json',
            },
        }

        return await TryFetch(url, fetchConfig)
    }

    function AddInvoice(invoice){
        var dateCol = document.createElement('td')
        dateCol.classList.add('p-1')
        const now = new Date(invoice.created_at);
        const year = now.getFullYear();
        const month = (now.getMonth() + 1).toString().padStart(2, '0');
        const day = now.getDate().toString().padStart(2, '0');
        const formattedDate = `${day}/${month}/${year}`;
        dateCol.innerHTML = formattedDate

        var conceptCol = document.createElement('td')
        conceptCol.classList.add('p-1')
        conceptCol.innerHTML = invoice.reason

        var priceCol = document.createElement('td')
        priceCol.classList.add('p-1')
        priceCol.innerHTML = parseFloat(invoice.total).toFixed(2)

        var seeCol = document.createElement('td')
        seeCol.classList.add('p-1')
        var seeLink = document.createElement('a')
        seeLink.classList.add('h6')
        seeLink.href = '<?= $base_url ?>/views/detailers/see_invoice.php'
        seeLink.target = '_blank'
        seeLink.innerHTML = 'Ver'
        seeLink.classList.add('fw-bold')
        seeCol.appendChild(seeLink)

        var row = document.createElement('tr')
        row.classList.add('text-center')
        row.classList.add('fs-5')        
        row.appendChild(dateCol)
        row.appendChild(conceptCol)
        row.appendChild(priceCol)
        row.appendChild(seeCol)
        
        invoiceTable.appendChild(row)
    }

    function ClearInvoices(){
        for (const child of invoiceTable.children) {
            child.remove()
        }
    }

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

    function AddProduct(){
        BuildProductRow()
        $(".select2").select2({width:'100%'});

        var oldId = nextProduct
        $('#product-id-' + String(nextProduct)).on('select2:select', async function (e) {
            var priceInput = document.getElementById('product-baseprice-' + String(oldId))
            var price = 0
            for(let i = 0; i < e.target.childNodes.length; i++){
                var node = e.target.childNodes[i]
                if(node.selected){
                    price = productPrices[node.innerHTML]
                    priceInput.value = price
                }
            }

            var total = price
            if(targetAccount['scholarship_coverage'] !== null){
                total = parseFloat(price) * (parseFloat(targetAccount['scholarship_coverage']) / 100)
            }

            console.log(targetAccount['scholarship_coverage'])

            document.getElementById('product-total-' + String(oldId)).value = total
        });        
        nextProduct++
    }

    function BuildProductRow(){
        var productId = String(nextProduct)

        var productCol = GetNewProductColumn(productId)        
        var monthCol = GetNewMonthColumn(productId)
        var completeCol = GetNewCompleteColumn(productId)
        var basePriceCol = GetNewBasePriceColumn(productId)
        var scholarshipCol = GetNewScholarshipDiscountColumn(productId)
        var totalCol = GetNewTotalColumn(productId)
        var eraseCol = GetNewEraseButtonColumn(productId)      

        var row = document.createElement('tr')
        row.classList.add('text-center', 'fs-5')
        row.id = "product-row-" + productId
        row.appendChild(productCol)
        row.appendChild(monthCol)
        row.appendChild(completeCol)
        row.appendChild(basePriceCol)
        row.appendChild(scholarshipCol)
        row.appendChild(totalCol)
        row.appendChild(eraseCol)

        productTable.appendChild(row)
    }

    function DeleteProductRow(id){
        document.getElementById('product-row-' + id).remove()
    }

    function AddClassesToSelect(select){
        select.classList.add('form-control', 'col-12', 'col-md-8', 'select2')
    }

    function GetNewProductColumn(productId){
        var productCol = document.createElement('td')
        var productSelect = document.createElement('select')
        var option = document.createElement('option')
        option.innerHTML = "&nbsp"
        productSelect.appendChild(option)
        AddClassesToSelect(productSelect)        
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
        var monthSelect = document.createElement('select')
        var option = document.createElement('option')
        option.innerHTML = "&nbsp"
        monthSelect.appendChild(option)
        AddClassesToSelect(monthSelect)
        buffer = "product-month-" + productId
        monthSelect.id = buffer
        monthSelect.name = buffer
        for(let key in months){
            var option = document.createElement('option')
            option.value = key
            option.innerHTML = months[key]
            monthSelect.appendChild(option)
        }
        monthCol.appendChild(monthSelect)
        return monthCol
    }

    function GetNewCompleteColumn(productId){
        var completeCol = document.createElement('td')
        completeCol.classList.add('align-middle')
        var completeCheckbox = document.createElement('input')
        completeCheckbox.type = 'checkbox'
        completeCheckbox.name = 'complete'
        completeCheckbox.value = 1
        completeCheckbox.checked = true
        buffer = "product-complete-" + productId
        completeCheckbox.id = buffer
        completeCheckbox.name = buffer
        completeCol.appendChild(completeCheckbox)
        return completeCol
    }

    function GetNewBasePriceColumn(productId){
        var basePriceCol = document.createElement('td')
        var basePriceInput = document.createElement('input')
        basePriceInput.type = 'text'
        basePriceInput.disabled = true
        basePriceInput.id = "product-baseprice-" + productId
        basePriceInput.classList.add('form-control')
        basePriceCol.appendChild(basePriceInput)
        return basePriceCol
    }

    function GetNewScholarshipDiscountColumn(productId){
        var scholarshipCol = document.createElement('td')
        var scholarshipInput = document.createElement('input')
        scholarshipInput.type = 'text'
        scholarshipInput.disabled = true
        scholarshipInput.id = "product-scholarship-" + productId
        scholarshipInput.classList.add('form-control', 'scholarship-input')
        scholarshipCol.appendChild(scholarshipInput)
        return scholarshipCol
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


</script>
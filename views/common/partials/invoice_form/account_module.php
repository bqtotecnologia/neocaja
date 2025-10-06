<script>
    $('#account').on('select2:select', async function (e) {
        ClearInvoices()
        if(e.target.value !== ''){
            await AccountSelecting(e)
        }
    });
    
    async function AccountSelecting(e){
        CleanProducts()
        var error = false
        targetAccount = await GetAccountData(e.target.value)
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

            var accountMonths = await GetAccountState(e.target.value, '<?= $periodId ?>')
            invoiceTable.innerHTML = ''
            await DisplayDebt(e.target.value, '<?= $periodId ?>')
            await DisplayInvoices(accountMonths.data)

            ShowScholarship()
            //AddProduct()
            DisplayDefaultProduct()        
            if(typeof debtData !== "string"){
                //UpdateDefaultProducts(debtData.data)
            }
            UpdateProductsPrice()
        }
    }    
</script>
<script>
    $('#account').on('select2:select', async function (e) {
        ClearInvoices()
        if(e.target.value !== ''){
            await AccountSelecting(e)
        }
    });
    
    async function AccountSelecting(e){
        var accountButton = document.getElementById('account-link')
        accountButton.classList.add('d-none')
        debtContainer.classList.add('d-none')
        var accountMonths = await GetAccountState(e.target.value, '<?= $periodId ?>')
        invoiceTable.innerHTML = ''
        CleanProducts()
            
        if(typeof accountMonths !== "string"){
            await DisplayDebt(e.target.value, '<?= $periodId ?>')
            targetAccount = await GetAccountData(e.target.value)
            targetAccount = targetAccount.data
            accountButton.classList.remove('d-none')
            accountButton.href  = '<?= $base_url ?>' + '/views/detailers/account_details.php?id=' + targetAccount.id
            await DisplayInvoices(accountMonths.data)
        }

        AddProduct()
        DisplayDefaultProduct()        
        if(typeof debtData !== "string"){
            UpdateDefaultProducts(debtData.data)
        }
        UpdateProductsPrice()
    }    
</script>
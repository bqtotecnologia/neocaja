<script>
    async function DisplayInvoices(invoices){
        if(Object.keys(invoices).length > 0){
            invoiceContainer.classList.remove('d-none')
            for(let key in invoices){
                if(youngestPayableMonth === null)
                    youngestPayableMonth = GetMonthNumberByName(key)

                AddInvoice(key, invoices[key])
            }
        }
    }

    function ClearInvoices(){
        for (const child of invoiceTable.children) {
            child.remove()
        }
    }

    async function DisplayDebt(account, period){
        debtData = await GetDebtOfAccountOfPeriod(account,period)        
        if(typeof debtData !== "string"){
            BuildDebtTable(debtData.data)
        }
    }
</script>
<script>
    function DisplayInvoices(period){
        invoiceContainer.classList.remove('d-none')
        invoiceTable.innerHTML = ''

        if(accountStates.data[period] === undefined)
            return

        var invoices = accountStates.data[period]
        if(Object.keys(invoices).length > 0){
            invoiceContainer.classList.remove('d-none')
            for(let key in invoices){               
                AddInvoice(key, invoices[key])
            }

            for(let key in invoices){               
                if(youngestPayableMonth === null && !paidMonths.includes(key))
                        youngestPayableMonth = GetMonthNumberByName(key)
            }
        }
    }

    function ClearInvoices(){
        for (const child of invoiceTable.children) {
            child.remove()
        }
    }

    function DisplayDebt(period){
        if(debtData.data[period] === undefined)
            BuildDebtTable(false)
        else
            BuildDebtTable(debtData.data[period])


    }
</script>
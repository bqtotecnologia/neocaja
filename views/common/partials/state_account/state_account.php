<script>
    async function DisplayDebt(account, period){
        debtData = await GetDebtOfAccountOfPeriod(account,period)        
        if(typeof debtData !== "string"){
            BuildDebtTable(debtData.data)
        }
    }

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

        if(retardDebt > 0 && !scholarshipped){
            // Tiene mora y NO está becado
            retardVES.classList.add('text-danger')  
            retardUSD.classList.add('text-danger')  
            retardVES.innerHTML = 'Bs. ' + GetPrettyCiphers(retardDebt * coinValues['Dólar'])
            retardUSD.innerHTML = GetPrettyCiphers(retardDebt) + '$'
        }
        else{
            retardVES.classList.add('text-success')  
            retardUSD.classList.add('text-success')  
            if(scholarshipped){
                retardVES.innerHTML = 'BECADO'
                retardUSD.innerHTML = 'BECADO'
            }
            else{
                retardVES.innerHTML = 'SIN DEUDA'
                retardUSD.innerHTML = 'SIN DEUDA'
            }
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
    </script>
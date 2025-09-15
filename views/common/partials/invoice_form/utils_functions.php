<script>  
    function GetPrettyCiphers(cipher){
        buffer = parseFloat(cipher).toFixed(2)
        var strCipher = String(buffer).replace('.', ',')
        var splits = strCipher.split(',')
        var intPart = splits[0]
        var decimalPart = splits[1]
        

        var finalStr = ''
        var positionCount = 0
        for(let i = intPart.length - 1; i >= 0; i--){
            var display = intPart[i]

            positionCount++
            if(positionCount === 4)
                display = display + '.'

            finalStr = display + finalStr
        }

        if(decimalPart !== undefined)
            finalStr = finalStr + ','  + decimalPart
        return finalStr
    }

    function ToggleReadOnly(input){
        input.readOnly = !input.readOnly
    }

    function GetMonthIsRetarded(targetMonth){
        var currentDate = new Date()
        var todayMonth = currentDate.getMonth() + 1
        result = false
        if(parseInt(targetMonth) < todayMonth && yearOfNextMonth === currentDate.getFullYear()){
            result = true
        }
        else if(parseInt(targetMonth) === todayMonth && parseInt(currentDate.getDate()) > retardMaxDay){
            result = true
        }
        return result
    }
</script>
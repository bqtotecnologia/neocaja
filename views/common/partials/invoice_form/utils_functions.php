<script>  
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
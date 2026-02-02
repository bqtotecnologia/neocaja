<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <input type="text" style="font-size:50px; text-align:right" id="input" value="0,00" onkeypress="PriceFieldBiehavior(event)" onkeydown="DeleteWrittenValue(event)">
    <button onclick="Test()">Prueba</button>
</body>

<script>
    const input = document.getElementById('input')
    input.focus()

    const decimalNumbers = 2
    let logicValue = 0.00
    let visibleValue = '0,00'
    let writtenValue = ''

    function Test(){
        input.focus()
        console.log('Position: ' + input.selectionStart)
        //input.setSelectionRange(0,input.value.length)
    }



    function PriceFieldBiehavior(e){
        e.preventDefault()
        if(!['1','2','3','4','5','6','7','8','9','0'].includes(e.key)){
            input.value = visibleValue
            return
        }

        writtenValue += e.key
        CalculateInputValue()        
    }

    function CalculateInputValue(){
        var finalValue = TransformWrittenValue()        
        input.value = finalValue
    }

    function DeleteWrittenValue(e){
        if(['Backspace','Delete'].includes(e.key)){
            e.preventDefault()
            if(writtenValue !== '')
                writtenValue = writtenValue.slice(0, writtenValue.length - 1)

            CalculateInputValue()
        }
    }

    function TransformWrittenValue(){
        console.log(writtenValue)
        var result = ''
        var size = writtenValue.length
        if(size === 0)
            result = '0,00'
        else if(size === 1)
            result = '0,0' + writtenValue
        else if(size === 2)
            result = '0,' + writtenValue
        else{
            var comma = false            
            for(let i = 0; i < size; i++){

                if(i >= size - 2 && comma === false){
                    result += ','
                    comma = true
                }

                result += writtenValue[i]
            }

            var splits = result.split(',')
            var integerPart = splits[0]
            var decimalPart = splits[1]

            var parsedIntegerPart = ''
            var pointCount = 0
            for(let i = integerPart.length - 1; i >= 0 ; i--){
                
                parsedIntegerPart = integerPart[i] + parsedIntegerPart
                pointCount++
                if(pointCount === 3){
                    parsedIntegerPart = '.' + parsedIntegerPart
                    pointCount = 0
                }
            }

            result = parsedIntegerPart + ',' + decimalPart
            if(result[0] === '.')
                result = result.slice(1, result.length)
        }

        return result

    }

</script>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <input type="text" style="font-size:50px; text-align:right" id="input" value="0,00" onkeypress="PriceFieldBiehavior(event)" onkeydown="DeleteWrittenValue(event)">
    <button onclick="GetFixedCursorPosition()">Prueba</button>
</body>

<script>
    const input = document.getElementById('input')
    input.focus()
    input.setSelectionRange(4,4)

    const decimalNumbers = 2
    let logicValue = 0.00
    let visibleValue = '0,00'
    let writtenValue = ''

    function PriceFieldBiehavior(e){
        e.preventDefault()
        if(!['1','2','3','4','5','6','7','8','9','0'].includes(e.key)){
            input.value = visibleValue
            return
        }

        var cursorPosition = input.selectionStart
        var oldValueSize = input.value.length
        var parts = GetWrittenValuePartsRegardCursorPosition()
        writtenValue = parts.firstPart + e.key + parts.secondPart

        CalculateInputValue()
        var newValueSize = input.value.length
        var diff = newValueSize - oldValueSize
        
        if(diff > 1)
            diff -= 1

        if(parts.firstPart !== '' && parts.secondPart !== '')
            diff -= 1

        input.setSelectionRange(cursorPosition + 1 + diff, cursorPosition + 1 + diff)
    }

    // Returns the input cursor position regard writtenValue position
    function GetWrittenValuePartsRegardCursorPosition(){
        var initialPosition = input.selectionStart
        var writePosition = initialPosition


        if(writePosition === 0)
            writePosition = -1

        if(writePosition === writtenValue.length)
            writePosition = writtenValue.length

        var obstacles = 0 // the number of '.' and ','
        for(let i = 0; i < writePosition; i++){
            if(['.', ','].includes(input.value[i]))
                obstacles += 1
        }

        writePosition -= obstacles
        if(writePosition < 0)
            writePosition = -1

        var firstPart = writtenValue.slice(0, writePosition )
        var secondPart = writtenValue.slice(writePosition )

        return {
            firstPart: firstPart,
            secondPart: secondPart,
            finalPosition: writePosition,
            initialPosition: initialPosition
        }
    }


    function CalculateInputValue(){
        var finalValue = TransformWrittenValue()        
        input.value = finalValue
    }

    function DeleteWrittenValue(e){
        if(['Backspace','Delete'].includes(e.key)){
            e.preventDefault()

            var cursorPosition = input.selectionStart

            if(writtenValue !== ''){
                // backspace
                if(input.selectionStart === input.selectionEnd && input.selectionStart !== 0){                
                    var parts = GetWrittenValuePartsRegardCursorPosition()
                    var firstPart = parts.firstPart.slice(0, parts.firstPart.length - 1)
    
                    writtenValue = firstPart + parts.secondPart
                }
            }


            CalculateInputValue()

            //console.log('Posición del cursor ' + cursorPosition + dots)
            if(cursorPosition <= 0)
                input.setSelectionRange(0, 0)
            else
            input.setSelectionRange(cursorPosition - 1, cursorPosition - 1)
        }
    }

    function TransformWrittenValue(){
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
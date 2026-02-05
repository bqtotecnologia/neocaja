// This script require the next variable, declare it before call this file
// const delay = ms => new Promise(res => setTimeout(res, ms));

const amountInputs = document.getElementsByClassName('amount-input')

const admittedKeys = [
    '1','2','3','4','5','6','7','8','9','0',
    'Backspace','Delete',
    'ArrowLeft', 'ArrowDown', 'ArrowRight', 'ArrowUp',
    'F5',
]

for(let i = 0; i < amountInputs.length; i++){
    const input = amountInputs.item(i)
    input.value = '0,00'
    input.onkeydown = function() {}
    input.onkeyup = function() {}
    input.onkeypress = function() {}
    input.addEventListener('keypress', function(e) { KeyDetected(e, input) })
    input.addEventListener('keydown', function(e) { KeyDetected(e, input) })
    input.addEventListener('focus', function (e) { PlaceCursorAtEnd(e, input) })
}

async function PlaceCursorAtEnd(e, input){
    e.preventDefault()

    await delay(1)
    input.setSelectionRange(-1, -1)
} 

function KeyDetected(e, input){       
    if(!admittedKeys.includes(e.key))
        e.preventDefault()
    
    if(['1','2','3','4','5','6','7','8','9','0'].includes(e.key)){
        e.preventDefault()
        PriceFieldBiehavior(e, input)
    }
    else if(['Backspace','Delete'].includes(e.key)){
        e.preventDefault()
        DeleteWrittenValue(e, input)
    }
}

function GetWrittenValue(input){
    var value = input.value.replaceAll(',', '').replaceAll('.', '')
    value = String(parseInt(value))
    return value
}

function PriceFieldBiehavior(e, input){       
    var cursorPosition = input.selectionStart
    var oldValueSize = input.value.length
    var parts = GetWrittenValuePartsRegardCursorPosition(input)
    var writtenValue = parts.firstPart + e.key + parts.secondPart

    CalculateInputValue(input, writtenValue)
    var newValueSize = input.value.length
    var diff = newValueSize - oldValueSize
    
    if(diff > 1)
        diff -= 1

    if(parts.firstPart !== '' && parts.secondPart !== '')
        diff -= 1

    input.setSelectionRange(cursorPosition + 1 + diff, cursorPosition + 1 + diff)
}

// Returns the input cursor position regard writtenValue position
function GetWrittenValuePartsRegardCursorPosition(input){
    var initialPosition = input.selectionStart
    var writePosition = initialPosition
    var writtenValue = GetWrittenValue(input)

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


function CalculateInputValue(input, writtenValue){
    var finalValue = TransformWrittenValue(writtenValue)        
    input.value = finalValue
}

function DeleteWrittenValue(e, input){
    if(['Backspace','Delete'].includes(e.key)){
        var writtenValue = GetWrittenValue(input)
        if(writtenValue === ''){
            input.setSelectionRange(-1, -1)
            return
        }

        var cursorPosition = input.selectionStart

        if(input.selectionStart !== input.selectionEnd)
            return

        // delete key
        if(e.key === 'Backspace'){
            if(input.selectionStart !== 0){
                var parts = GetWrittenValuePartsRegardCursorPosition(input)
                var firstPart = parts.firstPart.slice(0, parts.firstPart.length - 1)

                writtenValue = firstPart + parts.secondPart
            }                    
        } // supr key
        else if(e.key === 'Delete'){
            if(input.selectionStart !== input.value.length){
                var parts = GetWrittenValuePartsRegardCursorPosition(input)
                var secondPart = parts.secondPart.slice(1, parts.secondPart.length)
                
                writtenValue = parts.firstPart + secondPart
            }
        }

        var oldValue = input.value
        CalculateInputValue(input, writtenValue)

        if(writtenValue === ''){
            input.setSelectionRange(-1, -1)
            return
        }

        var newValue = input.value

        var diff = oldValue.length - newValue.length
        if(diff < 0)
            diff = 0

        if(e.key === 'Backspace'){
            diff = diff * -1
        }
        else if(e.key === 'Delete'){
            if(diff !== 0){
                diff -= 1

                if(diff === 1)
                    diff = -1
            }

            if(oldValue[cursorPosition] === '.' || oldValue[cursorPosition] === ','){
                diff += 1
            }
        }

        if(cursorPosition <= 0)
            input.setSelectionRange(0, 0)
        else
            input.setSelectionRange(cursorPosition + diff, cursorPosition + diff)
    }
}

function TransformWrittenValue(writtenValue){
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
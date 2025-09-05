<script>
    $('#igtf-coin').on('select2:select', async function (e) {
        RefreshIGTF()
    });

    const priceIGTF = document.getElementById('igtf-price')
    priceIGTF.addEventListener('change', function(e) {RefreshIGTF()})

    function RefreshIGTF(){
        var rate = 0
        var coinName = null

        var coinSelect = document.getElementById('igtf-coin')
        for(let i = 0; i < coinSelect.childNodes.length; i++){
            var node = coinSelect.childNodes[i]
            if(node.selected){
                coinName = node.innerHTML
            }
        }

        if(coinName !== '&nbsp;')
            rate = coinValues[coinName]

        var price = 0
        var priceInput = document.getElementById('igtf-price')
        if(priceInput.value !== '')            
            price = parseFloat(priceInput.value)

        var total = parseFloat(price * rate).toFixed(2)
        
        var igtfTotalInput = document.getElementById('igtf-total')
        igtfTotalInput.value = total
    }
</script>
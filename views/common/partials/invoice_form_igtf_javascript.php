<script>
    const igtf_table = document.getElementById('igtf-table')
    const igtf_method = document.getElementById('igtf-method')
    const igtf_bank = document.getElementById('igtf-bank')
    const igtf_salepoint = document.getElementById('igtf-salepoint')
    const igtf_document = document.getElementById('igtf-document')
    const igtf_price = document.getElementById('igtf-price')
    const igtf_coin = document.getElementById('igtf-coin')
    const igtf_total = document.getElementById('igtf-total')
    const igtf_total_label = document.getElementById('igtf-total-label')

    const igtf_rate = 0.03

    $('#igtf-coin').on('select2:select', async function (e) {
        RefreshIGTF()
    });

    igtf_price.addEventListener('change', function(e) {RefreshIGTF()})

    function RefreshIGTF(){
        var rate = 0
        var coinName = null

        
        for(let i = 0; i < igtf_coin.childNodes.length; i++){
            var node = igtf_coin.childNodes[i]
            if(node.selected){
                coinName = node.innerHTML
            }
        }

        if(coinName !== '&nbsp;')
            rate = coinValues[coinName]

        var price = 0
        
        if(igtf_price.value !== '')            
            price = parseFloat(igtf_price.value)

        var total = parseFloat(price * rate).toFixed(2)
        
        
        igtf_total.value = total
    }

    function DisableIGTF(){
        igtf_table.classList.add('d-none')
        HandleIGTF(true)
    }

    function EnableIGTF(){        
        igtf_table.classList.remove('d-none')
        HandleIGTF(false)
        
    }

    function UpdateIGTF(total){
        var igtf = (total * igtf_rate).toFixed(2)
        var igtfUSD = (igtf / coinValues['Dólar']).toFixed(2)
        igtf_total_label.innerHTML = '3% IGTF: Bs. ' + igtf + ' (' + igtfUSD + '$)'
    }

    function HandleIGTF(disabling){    
        igtf_price.disabled = disabling
        igtf_coin.disabled = disabling
        igtf_method.disabled = disabling
        igtf_bank.disabled = disabling
        igtf_salepoint.disabled = disabling
        igtf_document.disabled = disabling
    }
</script>
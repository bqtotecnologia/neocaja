<script>
    rateDate.addEventListener('change', async function(e) { await ChangingRateDate(e) })

    async function ChangingRateDate(e){
        if(e.target.value !== '')
            await GetCoinRatesOfDay(e.target.value)
    }

    async function GetCoinRatesOfDay(date){
        var result = await FetchCoinRatesOfDay(date)
            
        if(typeof result !== "string"){
            coinValues = result['data']

            for(let key in coinValues){
                const targetElement = document.getElementById(key + '-rate')
                if(targetElement !== null){
                    targetElement.innerHTML = coinValues[key]
                }
            }

        }

        UpdateProductsPrice()
        RefreshPaymentMethods()
    }
</script>
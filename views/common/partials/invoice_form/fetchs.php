<script>
    async function FetchCoinRatesOfDay(date){
        var url = '<?= $base_url ?>/api/get_coin_values_of_date.php?date=' + date
        var fetchConfig = {
            method: 'GET', 
            headers: {
                'Content-Type': 'application/json',
            },
        }

        return await TryFetch(url, fetchConfig)
    }

    async function GetAccountData(account){
        var url = '<?= $base_url ?>/api/get_account_data.php?account=' + account
        var fetchConfig = {
            method: 'GET', 
            headers: {
                'Content-Type': 'application/json',
            },
        }

        return await TryFetch(url, fetchConfig)
    }  

    async function GetAccountState(account, period){
        var url = '<?= $base_url ?>/api/get_account_state.php?account=' + account + '&period=' + period
        var fetchConfig = {
            method: 'GET', 
            headers: {
                'Content-Type': 'application/json',
            },
        }

        return await TryFetch(url, fetchConfig)
    }

    async function GetDebtOfAccountOfPeriod(account,period){
        var url = '<?= $base_url ?>/api/get_account_debt.php?account=' + account + '&period=' + period
        var fetchConfig = {
            method: 'GET', 
            headers: {
                'Content-Type': 'application/json',
            },
        }

        return await TryFetch(url, fetchConfig)
    }
</script>
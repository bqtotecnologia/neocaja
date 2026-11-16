<script>
    async function GetRealProducts(codes){
        var url = '<?= $base_url ?>/api/get_products_of_account_by_codes.php'

        const dataToSend = {
            'cedula': '<?= $_SESSION['neocaja_cedula'] ?>',
            'cedula': '<?= $_SESSION['neocaja_cedula_letter'] ?>',
            'codes': codes
        }

        var fetchConfig = {
            method: 'POST', 
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend)
        }

        return await TryFetch(url, fetchConfig)
    }

    async function FetchUSDValueOfDay(date){
        var url = '<?= $base_url ?>/api/get_usd_value_of_date.php'

        const dataToSend = {
            'date': date,
        }

        var fetchConfig = {
            method: 'POST', 
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend)
        }

        return await TryFetch(url, fetchConfig)
    }

</script>
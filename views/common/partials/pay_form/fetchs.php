<script>
    async function GetAvailableProducts(){
        var url = '<?= $base_url ?>/api/get_available_products_of_account.php'

        const dataToSend = {
            'period': '<?= $current_period['idperiodo'] ?>',
            'cedula': '<?= $_SESSION['neocaja_cedula'] ?>'
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
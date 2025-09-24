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

    async function GetRealProducts(codes){
        var url = '<?= $base_url ?>/api/get_products_of_account_by_code.php'

        const dataToSend = {
            'period': '<?= $current_period['idperiodo'] ?>',
            'cedula': '<?= $_SESSION['neocaja_cedula'] ?>',
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

    async function FetchPayment(data){
        var url = '<?= $base_url ?>/api/register_payment.php'

        const dataToSend = {
            'cedula': data['cedula'],
            'codes': data['codes'],
            'document': data['document'],
            'ref': data['ref'],
            'price': data['price'],
            'payment_method_type': data['payment_method_type'],
            'payment_method': data['payment_method'],
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
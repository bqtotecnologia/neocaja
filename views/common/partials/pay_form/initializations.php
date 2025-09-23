<script>
    const pageBody = document.getElementById('page-body')
    const cartTable = document.getElementById('cart-table')
    const checkoutCartTable = document.getElementById('checkout-cart-table')

    const checkoutContainer = document.getElementById('checkout-container')    
    const productContainer = document.getElementById('product-container')    
    const cartContainer = document.getElementById('cart-container')
    const checkoutInputContainer = document.getElementById('checkout-inputs-container')    

    const totalUSDLabel = document.getElementById('total-usd')
    const totalVESLabel = document.getElementById('total-bs')
    const totalToPay = document.getElementById('total-to-pay')

    const methodTypeSelect = document.getElementById('payment-method-type')
    const methodSelectContainer = document.getElementById('payment-method-container')
    const methodSelect = document.getElementById('payment-method')
    const methodDataContainer = document.getElementById('payment-method-display')
    const methodDataTable = document.getElementById('payment-method-table')

    const cedulaInput = document.getElementById('cedula')
    const priceInput = document.getElementById('price')
    const refInput = document.getElementById('ref')
    
    const usdValue = parseFloat('<?= $usdValue['price'] ?>')
    
    let paymentMethods = {}
    const mobile_payments = []
    const transfers = []
    let selectedProducts = []
    let productList = []
    let proceedToPay = false

    methodTypeSelect.addEventListener('change', function(e) { SelectPaymentMethodType(e.target.value) })
    methodSelect.addEventListener('change', function(e) { SelectPaymentMethod(e.target.value, methodTypeSelect.value) })
</script>


<?php foreach($products as $product) { ?>
    <script>
        var product = {
            'name': '<?= $product['name'] ?>',
            'price': parseFloat('<?= $product['price'] ?>'),
            'code': '<?= $product['code'] ?>',
        }
        productList.push(product)
    </script>
<?php } ?>

<?php foreach($mobile_payments as $mobile_payment) { ?>
    <script>
        var mobile_payment = {
            'id': '<?= $mobile_payment['id'] ?>',
            'phone': '<?= $mobile_payment['phone'] ?>',
            'document': '<?= $mobile_payment['document_letter'] . $mobile_payment['document_number'] ?>',
            'bank': '<?= $mobile_payment['bank'] ?>',
        }
        mobile_payments.push(mobile_payment)
    </script>
<?php } ?>

<?php foreach($transfers as $transfer) { ?>
    <script>
        var transfer = {
            'id': '<?= $transfer['id'] ?>',
            'account_number': '<?= $transfer['account_number'] ?>',
            'document': '<?= $transfer['document_letter'] . $transfer['document_number'] ?>',
            'bank': '<?= $transfer['bank'] ?>',
        }
        transfers.push(transfer)
    </script>
<?php } ?>

<script>
    paymentMethods = {
        'mobile_payment': mobile_payments,
        'transfer': transfers
    }
</script>

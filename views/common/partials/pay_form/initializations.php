<script>
    const pageBody = document.getElementById('page-body')
    const cartTable = document.getElementById('cart-table')
    const checkoutCartTable = document.getElementById('checkout-cart-table')

    const checkoutContainer = document.getElementById('checkout-container')    
    const productContainer = document.getElementById('product-container')    
    const cartContainer = document.getElementById('cart-container')
    const checkoutDataContainer = document.getElementById('checkout-data-container')
    const paymentMethodDisplayContainer = document.getElementById('payment-method-display')

    const totalUSDLabel = document.getElementById('total-usd')
    const totalVESLabel = document.getElementById('total-bs')
    const totalToPay = document.getElementById('total-to-pay')

    const paymentMethodType = document.getElementById('payment-method-type')
    const paymentMethodSelection = document.getElementById('payment-method')

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

    paymentMethodType.addEventListener('change', function(e) { SelectPaymentMethodType(e.target.value) })
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
        'mobile_payments': mobile_payment,
        'transfers': transfers
    }
</script>

<script>
    const pageBody = document.getElementById('page-body')
    const cartTable = document.getElementById('cart-table')
    const checkoutCartTable = document.getElementById('checkout-cart-table')
    const checkoutContainer = document.getElementById('checkout-container')    
    const productContainer = document.getElementById('product-container')
    const cartContainer = document.getElementById('cart-container')
    const totalUSDLabel = document.getElementById('total-usd')
    const totalVESLabel = document.getElementById('total-bs')
    const totalToPay = document.getElementById('total-to-pay')
    const usdValue = parseFloat('<?= $usdValue['price'] ?>')

    let selectedProducts = []
    let productList = []
    let proceedToPay = false
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

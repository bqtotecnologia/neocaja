<script>
    const cartTable = document.getElementById('cart-table')
    const totalUSDLabel = document.getElementById('total-usd')
    const totalVESLabel = document.getElementById('total-bs')
    const usdValue = parseFloat('<?= $usdValue['price'] ?>')

    let selectedProducts = []
    let productList = []
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

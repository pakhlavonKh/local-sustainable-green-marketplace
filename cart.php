<?php require 'config.php'; if (!is_logged_in()) redirect('login.php'); ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepet</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container" style="padding: 2rem 1rem;">
    <h2 style="color:var(--green-600); margin-bottom:1.5rem;">Sepetiniz</h2>
    <table class="table">
        <thead><tr><th>Ürün</th><th>Fiyat</th><th>Adet</th><th>Toplam</th><th></th></tr></thead>
        <tbody id="cart-body"></tbody>
    </table>
    <div style="text-align:right; margin-top:1rem; font-size:1.3rem;"><strong>Toplam: ₺<span id="cart-total">0.00</span></strong></div>
    <button id="checkout-btn" class="btn btn-success" style="margin-top:1rem; padding:0.75rem 1.5rem; font-size:1.1rem;">Ödeme Yap (Demo)</button>
</div>
<?php include 'footer.php'; ?>
<script src="cart.js"></script>
</body>
</html>
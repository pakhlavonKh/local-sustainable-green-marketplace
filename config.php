<?php require 'functions.php'; 
if (!is_logged_in()) redirect('login.php'); 
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetim</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container" style="padding: 2rem 1rem;">
    <h2 style="color: var(--green-600); margin-bottom: 1.5rem; text-align: center;">Sepetiniz</h2>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?php echo h($_GET['msg']); ?></div>
    <?php endif; ?>

    <div id="empty-cart" style="text-align: center; padding: 3rem; display: none;">
        <p style="font-size: 1.2rem; color: var(--gray-600);">Sepetiniz boş.</p>
        <a href="index.php" class="btn btn-success" style="margin-top: 1rem;">Alışverişe Devam Et</a>
    </div>

    <table class="table" id="cart-table" style="display: none;">
        <thead>
            <tr>
                <th>Ürün</th>
                <th style="text-align: right;">Fiyat</th>
                <th style="text-align: center;">Adet</th>
                <th style="text-align: right;">Toplam</th>
                <th style="text-align: center;">İşlem</th>
            </tr>
        </thead>
        <tbody id="cart-body"></tbody>
    </table>

    <div id="cart-summary" style="display: none; text-align: right; margin-top: 1.5rem; font-size: 1.4rem;">
        <strong>Genel Toplam: ₺<span id="cart-total">0.00</span></strong>
        <button id="checkout-btn" class="btn btn-success" style="margin-left: 1rem; padding: 0.75rem 1.5rem; font-size: 1.1rem;">
            Ödemeye Geç (Demo)
        </button>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="cart.js"></script>
</body>
</html>
<?php require 'functions.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeşil Pazar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="padding: 2rem 1rem;">
        <div class="hero-header">
            <h1>Yerel. Sürdürülebilir. Yeşil.</h1>
            <p>Topluluğunuzdan çevre dostu ürünleri alın ve satın</p>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success"><?php echo h($_GET['msg']); ?></div>
        <?php endif; ?>

        <div class="grid">
            <?php
            $stmt = $pdo->query("SELECT p.*, u.name AS seller FROM products p JOIN users u ON p.user_id=u.id ORDER BY p.created_at DESC");
            while ($p = $stmt->fetch()):
            ?>
                <div class="card">
                    <?php if ($p['image']): ?>
                        <img src="<?php echo h($p['image']); ?>" alt="<?php echo h($p['title']); ?>" class="card-img">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/300x200?text=Resim+Yok" class="card-img">
                    <?php endif; ?>
                    <div class="card-body">
                        <h3 class="card-title"><?php echo h($p['title']); ?></h3>
                        <p class="card-text"><?php echo h($p['description']); ?></p>
                        <div class="price">₺<?php echo number_format($p['price'], 2); ?></div>
                        <div class="seller">Satıcı: <?php echo h($p['seller']); ?></div>

                        <?php if (is_logged_in() && $_SESSION['user_id'] == $p['user_id']): ?>
                            <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="btn btn-outline-primary">Düzenle</a>
                            <a href="delete_product.php?id=<?php echo $p['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Silmek istediğinizden emin misiniz?');">Sil</a>
                        <?php endif; ?>

                        <button class="btn btn-success add-to-cart" style="margin-top: 0.5rem;" data-id="<?php echo $p['id']; ?>" data-title="<?php echo h($p['title']); ?>" data-price="<?php echo $p['price']; ?>">
                            Sepete Ekle
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="cart.js"></script>
</body>
</html>
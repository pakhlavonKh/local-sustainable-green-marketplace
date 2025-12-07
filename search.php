<?php
// Simple search implementation that works with local products array or MongoDB
require_once 'lang_config.php';
require_once 'data_products.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if ($q !== '') {
    $qLower = mb_strtolower($q, 'UTF-8');

    // If MongoDB available and connected, prefer it
    if (function_exists('getDBConnection')) {
        $db = @getDBConnection();
        if ($db) {
            try {
                $collection = $db->products;
                $filter = [
                    '$or' => [
                        ['title' => new MongoDB\BSON\Regex($q, 'i')],
                        ['title_tr' => new MongoDB\BSON\Regex($q, 'i')],
                        ['desc' => new MongoDB\BSON\Regex($q, 'i')]
                    ]
                ];
                $cursor = $collection->find($filter);
                foreach ($cursor as $doc) {
                    $item = (array)$doc;
                    $results[] = $item;
                }
            } catch (Exception $e) {
                // fallback to local array
            }
        }
    }

    // Fallback: search local $products_db array
    if (empty($results)) {
        foreach ($products_db as $p) {
            $hay = mb_strtolower($p['title'] . ' ' . ($p['title_tr'] ?? '') . ' ' . ($p['desc'] ?? ''), 'UTF-8');
            if (mb_stripos($hay, $q) !== false || mb_stripos($hay, $qLower) !== false) {
                $results[] = $p;
            }
        }
    }
}

?>

<?php include 'navbar.php'; ?>
<div class="container" style="padding:40px 20px;">
    <h1>Search results for: <?php echo htmlspecialchars($q); ?></h1>

    <?php if ($q === ''): ?>
        <p>Please enter a search term.</p>
    <?php else: ?>
        <?php if (empty($results)): ?>
            <p>No results found.</p>
        <?php else: ?>
            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap:20px; margin-top:20px;">
                <?php foreach ($results as $r): ?>
                    <a href="product_detail.php?id=<?php echo urlencode($r['id']); ?>" class="product-card" style="text-decoration:none;">
                        <div class="card-image-wrapper" style="height:160px;"><img src="<?php echo htmlspecialchars($r['image']); ?>" alt="" style="width:100%; height:100%; object-fit:cover;"></div>
                        <div class="card-info">
                            <div class="card-title"><?php echo htmlspecialchars($r['title'] ?? ($r['name'] ?? 'Product')); ?></div>
                            <div class="card-price"><?php echo number_format($r['price'] ?? 0, 2); ?> <?php echo isset($text['currency']) ? $text['currency'] : 'TL'; ?></div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

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
            } catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
                error_log('MongoDB timeout in search.php: ' . $e->getMessage());
            } catch (Exception $e) {
                error_log('MongoDB error in search.php: ' . $e->getMessage());
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
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <title>Search Results - Leaf Market</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🍃</text></svg>">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container" style="padding:40px 20px;">
    <h1>Search results for: <?php echo htmlspecialchars($q); ?></h1>

    <?php if ($q === ''): ?>
        <p>Please enter a search term.</p>
    <?php else: ?>
        <?php if (empty($results)): ?>
            <p>No results found.</p>
        <?php else: ?>
            <?php require_once 'product_card.php'; ?>
            <div class="product-grid">
                <?php foreach ($results as $r): 
                    $product_array = is_array($r) ? $r : (array)$r;
                    renderProductCard($product_array, [
                        'show_wishlist' => true,
                        'show_add_to_cart' => true
                    ]);
                endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

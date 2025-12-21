<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'lang_config.php';
require_once 'data_products.php';
require_once 'product_card.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if ($q !== '') {
    $qLower = mb_strtolower($q, 'UTF-8');

    // MongoDB Kontrolü (Orijinal kodundaki mantık)
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
                foreach ($cursor as $doc) { $results[] = (array)$doc; }
            } catch (Exception $e) { error_log($e->getMessage()); }
        }
    }

    // Yerel Dizi Araması (Orijinal kodundaki mantık)
    if (empty($results)) {
        foreach ($products_db as $p) {
            $hay = mb_strtolower($p['title'] . ' ' . ($p['title_tr'] ?? '') . ' ' . ($p['desc'] ?? ''), 'UTF-8');
            if (mb_stripos($hay, $q) !== false) {
                $results[] = $p;
            }
        }
    }
}

if (empty($results)) {
    echo '<div class="empty-msg" style="grid-column: 1/-1; text-align: center; padding: 40px;">';
    echo '<p>No results found.</p></div>';
} else {
    echo '<div class="product-grid">';
    foreach ($results as $r) {
        $product_array = is_array($r) ? $r : (array)$r;
        renderProductCard($product_array, [
            'show_wishlist' => true,
            'show_add_to_cart' => true
        ]);
    }
    echo '</div>';
}
?>
<?php
// API endpoint to fetch product details for modal
session_start();
require_once 'lang_config.php';
require_once 'db_connect.php';

header('Content-Type: application/json');

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Try to get product from MongoDB
$db = getDBConnection();
$product = null;

if ($db) {
    try {
        $collection = $db->products;
        $product = $collection->findOne(['id' => $product_id]);
        if ($product) {
            $product = (array)$product;
        }
    } catch (Exception $e) {
        error_log('Error fetching product: ' . $e->getMessage());
    }
}

// Fallback to data_products.php if MongoDB fails
if (!$product) {
    require_once 'data_products.php';
    $product = isset($products_db[$product_id]) ? $products_db[$product_id] : null;
}

if (!$product) {
    echo json_encode(['error' => 'Product not found']);
    exit;
}

// Calculate Eco Score
$base_score = 5;
if (isset($product['delivery_mode'])) {
    if ($product['delivery_mode'] == 'bike' || $product['delivery_mode'] == 'walk') $base_score += 3;
    elseif ($product['delivery_mode'] == 'public') $base_score += 2;
}
if (isset($product['packaging_type'])) {
    if ($product['packaging_type'] == 'plastic_free') $base_score += 2;
    elseif ($product['packaging_type'] == 'recycled') $base_score += 1;
}

$eco_score = min($base_score, 10);
$eco_width = ($eco_score * 10);

// Determine Badge Style
if ($eco_score >= 8) { 
    $eco_badge_class = 'eco-high'; 
    $eco_text = isset($text['impact_high']) ? $text['impact_high'] : 'Excellent'; 
} elseif ($eco_score >= 6) { 
    $eco_badge_class = 'eco-med'; 
    $eco_text = isset($text['impact_med']) ? $text['impact_med'] : 'Good'; 
} else { 
    $eco_badge_class = 'eco-low'; 
    $eco_text = isset($text['impact_low']) ? $text['impact_low'] : 'Standard'; 
}

// Language support
$lang = $_SESSION['lang'] ?? 'en';
$display_title = ($lang === 'tr' && !empty($product['title_tr'])) ? $product['title_tr'] : $product['title'];

// Prepare response
$response = [
    'id' => $product_id,
    'title' => $display_title,
    'title_raw' => $product['title'],
    'price' => $product['price'],
    'image' => $product['image'],
    'category' => isset($text['cat_' . $product['category']]) ? $text['cat_' . $product['category']] : ucfirst($product['category']),
    'description' => $product['desc'] ?? 'No description available.',
    'stock' => $product['stock'] ?? 0,
    'co2_saved' => $product['co2_saved'] ?? 0,
    'seller_id' => $product['seller_id'] ?? null,
    'seller_name' => $product['seller_name'] ?? 'Leaf Market',
    'distance' => $product['distance'] ?? 'Local',
    'eco_score' => $eco_score,
    'eco_width' => $eco_width,
    'eco_badge_class' => $eco_badge_class,
    'eco_text' => $eco_text,
    'reviews' => $product['reviews'] ?? [],
    'text' => [
        'currency' => $text['currency'] ?? 'TL',
        'add_cart' => $text['add_cart'] ?? 'Add to Cart',
        'eco_score_label' => $text['eco_score_label'] ?? 'Sustainability Score',
        'seller_profile' => $text['seller_profile'] ?? 'View Profile',
        'verified_buyer' => $text['verified_buyer'] ?? 'Verified',
        'reviews_title' => $text['reviews_title'] ?? 'Reviews',
        'low_stock' => $text['low_stock'] ?? 'Low Stock',
        'nav_login' => $text['nav_login'] ?? 'Login to Buy'
    ],
    'is_logged_in' => isset($_SESSION['user_id'])
];

echo json_encode($response);

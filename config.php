<?php
// config.php - NO DATABASE
session_start();

// CSRF token
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

// === FILE PATHS ===
define('DATA_DIR', __DIR__ . '/data');
define('USERS_FILE', DATA_DIR . '/users.json');
define('PRODUCTS_FILE', DATA_DIR . '/products.json');

// Create data folder if not exists
if (!is_dir(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}

// === HELPERS ===
if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('redirect')) {
    function redirect($url) {
        header("Location: $url");
        exit;
    }
}

if (!function_exists('h')) {
    function h($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}

// === JSON DB HELPERS ===
function read_json($file, $default = []) {
    if (!file_exists($file)) return $default;
    $content = file_get_contents($file);
    return $content ? json_decode($content, true) : $default;
}

function write_json($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Init empty files if missing
if (!file_exists(USERS_FILE)) write_json(USERS_FILE, []);
if (!file_exists(PRODUCTS_FILE)) write_json(PRODUCTS_FILE, []);
?>
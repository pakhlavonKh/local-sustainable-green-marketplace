<?php
require_once 'config.php';

function is_logged_in() { return isset($_SESSION['user_id']); }
function redirect($url) { header("Location: $url"); exit; }
function h($str) { return htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); }
?>
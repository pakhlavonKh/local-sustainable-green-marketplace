<?php
// Load the library
require_once __DIR__ . '/vendor/autoload.php';

// Simple .env loader: if a .env file exists in project root, load values into environment
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (!strpos($line, '=')) continue;
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        // strip optional surrounding quotes
        if ((substr($value,0,1) === '"' && substr($value,-1) === '"') || (substr($value,0,1) === "'" && substr($value,-1) === "'")) {
            $value = substr($value,1,-1);
        }
        // only set env if not already set in system
        if (getenv($name) === false) {
            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }
}

/**
 * Return a MongoDB\Database instance based on environment configuration.
 *
 * Priority:
 * - If `MONGODB_URI` is set and not a placeholder, it will be used directly.
 * - Otherwise, the URI is composed from `MONGODB_USER`, `MONGODB_PASS`, `MONGODB_HOST`, `MONGODB_DB`, and `MONGODB_OPTS`.
 */
function getDBConnection() {
    // Prefer full connection URI from environment
    $uri = getenv('MONGODB_URI') ?: false;

    // ignore placeholder URIs that contain angle brackets (e.g. <db_password>)
    if ($uri && (strpos($uri, '<') !== false || strpos($uri, '>') !== false)) {
        $uri = false;
    }

    // Database name requested (used when selecting the DB object)
    $dbName = getenv('MONGODB_DB') ?: 'leaf_market';

    if (!$uri) {
        $username = getenv('MONGODB_USER') ?: 'admin';
        $password = getenv('MONGODB_PASS') ?: '';
        $host = getenv('MONGODB_HOST') ?: 'local-sustainable-green.ygi7dra.mongodb.net';
        $opts = getenv('MONGODB_OPTS') ?: '?appName=Local-sustainable-green-marketplace';

        $encoded_pwd = urlencode($password);
        // compose a mongodb+srv URI that includes the database name and any options
        $uri = "mongodb+srv://{$username}:{$encoded_pwd}@{$host}/{$dbName}{$opts}";
    }

    try {
        $client = new MongoDB\Client($uri);
        // Return a MongoDB\Database instance
        return $client->selectDatabase($dbName);
    } catch (Exception $e) {
        // Log the error for debugging and return null so callers can handle missing DB
        error_log('MongoDB connection error: ' . $e->getMessage());
        return null;
    }
}

?>
<?php
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/vendor/autoload.php';

$db = getDBConnection();
if ($db === null) {
    echo "DB connection returned null\n";
    exit(1);
}

try {
    // run a simple command: ping server
    $command = new MongoDB\Driver\Command(['ping' => 1]);
    $client = $db->getManager();
    $result = $client->executeCommand($db->getDatabaseName(), $command);
    $arr = $result->toArray();
    echo "Ping result: ";
    var_export($arr);
    echo "\n";
    // list collections
    $collections = $db->listCollections();
    echo "Collections: ";
    foreach ($collections as $c) {
        echo $c->getName() . " ";
    }
    echo "\n";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    exit(1);
}

?>
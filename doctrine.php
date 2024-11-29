<?php
// doctrine.php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once 'vendor/autoload.php';

$dbConfig = require __DIR__ . '/config/database.php';

if (!$dbConfig || !isset($dbConfig['dbname'])) {
    file_put_contents('error_log.log', "Failed to load or decode database configuration\n", FILE_APPEND);
    die('Error: Failed to load database configuration.');
}

$paths = [__DIR__ . '/src/Entity'];
$isDevMode = true;

try {
    $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);

    $connectionOptions = [
        'driver' => $dbConfig['driver'],
        'host' => $dbConfig['host'],
        'port' => $dbConfig['port'],
        'dbname' => $dbConfig['dbname'],
        'user' => $dbConfig['user'],
        'password' => $dbConfig['password'],
    ];

    $entityManager = EntityManager::create($connectionOptions, $config);
} catch (\Exception $e) {
    file_put_contents('error_log.log', 'Doctrine setup error: ' . $e->getMessage() . "\n", FILE_APPEND);
    die('Error: Doctrine setup failed.');
}

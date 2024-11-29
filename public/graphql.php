<?php
// graphql.php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Content-Type: application/json');

use GraphQL\GraphQL;
use App\GraphQL\SchemaBuilder;
use Doctrine\ORM\EntityManager;
require_once '../vendor/autoload.php';  // Updated path for autoload.php
require_once '../doctrine.php';         // Updated path for doctrine.php

// Build the schema
$schema = SchemaBuilder::build($entityManager);

// Handle the request
$rawInput = file_get_contents('php://input');
$input = $rawInput ? json_decode($rawInput, true) : [];
$query = $input['query'] ?? '';
$variables = $input['variables'] ?? null;

try {
    $result = GraphQL::executeQuery($schema, $query, null, null, $variables);
    $output = $result->toArray();
} catch (\Exception $e) {
    file_put_contents('error_log.log', 'GraphQL execution error: ' . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n", FILE_APPEND);
    $output = ['errors' => [['message' => 'Internal server error']]];
}

header('Content-Type: application/json');
echo json_encode($output);

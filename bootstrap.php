<?php

use GraphQL\GraphQL;
use App\GraphQL\SchemaBuilder;
use Doctrine\ORM\EntityManagerInterface;

require 'vendor/autoload.php';
require 'doctrine.php';

$entityManager = $entityManager; // Assuming you have set up Doctrine here
$schema = SchemaBuilder::build($entityManager);

// Handle the raw input using PHP's built-in functions
$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);

$query = $input['query'] ?? '';
$variables = $input['variables'] ?? null;

try {
    $result = GraphQL::executeQuery($schema, $query, null, null, $variables);
    $output = $result->toArray();
} catch (\Exception $e) {
    $output = ['errors' => [['message' => $e->getMessage()]]];
}

// Set headers and output JSON response
header('Content-Type: application/json');
echo json_encode($output);

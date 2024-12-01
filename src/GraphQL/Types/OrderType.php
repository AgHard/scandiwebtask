<?php
// src/GraphQL/Types/OrderType.php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class OrderType extends ObjectType
{
    public function __construct(ProductType $productType)
    {
        parent::__construct([
            'name' => 'Order',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::id()),
                    'description' => 'The unique ID of the order',
                ],
                'totalAmount' => [
                    'type' => Type::nonNull(Type::float()),
                    'description' => 'The total amount of the order',
                ],
                'createdAt' => [
                    'type' => Type::nonNull(Type::string()), // Use string to represent DateTime
                    'description' => 'The date and time the order was created',
                ],
                'products' => [
                    'type' => Type::listOf(Type::nonNull($productType)), // Pass the ProductType object here
                    'description' => 'The list of products in the order',
                ],
                'orderDetails' => [
                    'type' => Type::string(),
                    'description' => 'Dynamic order details (e.g., delivery address, store location)',
                ],
            ]
        ]);
    }
}


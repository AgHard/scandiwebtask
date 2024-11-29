<?php
// src/GraphQL/Types/PriceType.php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class PriceType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Price',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::id()),
                    'description' => 'The unique ID of the price',
                ],
                'amount' => [
                    'type' => Type::nonNull(Type::float()),
                    'description' => 'The amount of the price',
                ],
                'currencyLabel' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'The currency label for the price (e.g., USD, EUR)',
                ],
                'currencySymbol' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'The currency symbol for the price (e.g., $, €)',
                ],
                'product' => [
                    'type' => Type::id(),
                    'description' => 'The ID of the product associated with this price',
                ]
            ]
        ]);
    }
}

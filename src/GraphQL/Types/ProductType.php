<?php
// src/GraphQL/Types/ProductType.php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ProductType extends ObjectType
{
    public function __construct(AttributeType $attributeType, GalleryType $galleryType, PriceType $priceType)
    {
        parent::__construct([
            'name' => 'Product',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::id()),
                    'description' => 'The unique ID of the product',
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'The name of the product',
                ],
                'description' => Type::string(),
                'inStock' => [
                    'type' => Type::nonNull(Type::boolean()),
                    'description' => 'Whether the product is in stock',
                ],
                'category' => [
                    'type' => Type::string(),
                    'description' => 'The category of the product',
                ],
                'prices' => [
                    'type' => Type::listOf(Type::nonNull($priceType)),
                    'description' => 'List of prices for the product',
                ],
                'galleries' => [
                    'type' => Type::listOf(Type::nonNull($galleryType)),
                    'description' => 'List of galleries for the product',
                ],
                'attributes' => [
                    'type' => Type::listOf(Type::nonNull($attributeType)),
                    'description' => 'List of attributes for the product',
                ],
                'imageUrl' => [
                    'type' => Type::string(),
                    'description' => 'The main image URL of the product',
                ],
            ]
        ]);
    }
}


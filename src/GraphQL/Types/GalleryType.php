<?php
// src/GraphQL/Types/GalleryType.php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class GalleryType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Gallery',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::id()),
                    'description' => 'The unique ID of the gallery',
                ],
                'imageUrl' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'The URL of the gallery image',
                ],
                'product' => [
                    'type' => Type::id(),
                    'description' => 'The ID of the product associated with this gallery',
                ],
            ]
        ]);
    }
}

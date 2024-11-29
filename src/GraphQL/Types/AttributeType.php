<?php
// src/GraphQL/Types/AttributeType.php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AttributeType extends ObjectType
{
    public function __construct(AttributeItemType $attributeItemType)
    {
        $config = [
            'name' => 'Attribute',
            'fields' => [
                'id' => [
                    'type' => Type::id(),
                ],
                'name' => [
                    'type' => Type::string(),
                ],
                'type' => [
                    'type' => Type::string(),
                ],
                'items' => [
                    'type' => Type::listOf(Type::nonNull($attributeItemType)), // Reuse the passed instance
                ],
            ],
        ];
        parent::__construct($config);
    }
}

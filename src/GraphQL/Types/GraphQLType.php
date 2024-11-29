<?php
// src/GraphQL/Types/GraphQLType.php

namespace App\GraphQL\Types;

interface GraphQLType
{
    /**
     * Build the GraphQL type definition.
     *
     * @return array
     */
    public function buildTypeDefinition(): array;
}

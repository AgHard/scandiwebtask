<?php
// src/GraphQL/Resolvers/Resolver.php

namespace App\GraphQL\Resolvers;

interface Resolver
{
    /**
     * Resolve data for GraphQL queries.
     *
     * @param array $args
     * @return mixed
     */
    public function resolve(array $args);
}

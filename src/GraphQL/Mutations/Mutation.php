<?php
// src/GraphQL/Mutations/Mutation.php

namespace App\GraphQL\Mutations;

interface Mutation
{
    /**
     * Execute the mutation logic.
     *
     * @param array $args
     * @return mixed
     */
    public function execute(array $args);
}

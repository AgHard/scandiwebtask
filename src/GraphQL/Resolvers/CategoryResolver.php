<?php
// src/GraphQL/Resolvers/CategoryResolver.php

namespace App\GraphQL\Resolvers;

use Doctrine\ORM\EntityManager;
use App\Entity\Category;

class CategoryResolver
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function resolveCategories()
    {
        // Fetch categories from the database using Doctrine
        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        
        if (!$categories) {
            throw new \Exception('No categories found.');
        }

        // Map each category to return only the needed fields using getters
        $categoryData = [];
        foreach ($categories as $category) {
            $categoryData[] = [
                'id' => $category->getId(),
                'name' => $category->getName()
            ];
        }

        return $categoryData;
    }
}

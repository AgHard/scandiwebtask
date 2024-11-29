<?php
// src/GraphQL/Resolvers/CategoryResolver.php

namespace App\GraphQL\Resolvers;

use Doctrine\ORM\EntityManager;
use App\Entity\Category;

<<<<<<< HEAD
class CategoryResolver implements Resolver
=======
class CategoryResolver
>>>>>>> 9af87b1 (visualchanges)
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

<<<<<<< HEAD
    /**
     * Resolve categories based on the provided arguments.
     *
     * @param array $args
     * @return array
     * @throws \Exception
     */
    public function resolve(array $args): array
    {
        if (isset($args['id'])) {
            return $this->formatCategory(
                $this->entityManager->getRepository(Category::class)->find($args['id'])
            );
        }

        $categories = $this->entityManager->getRepository(Category::class)->findAll();

=======
    public function resolveCategories()
    {
        // Fetch categories from the database using Doctrine
        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        
>>>>>>> 9af87b1 (visualchanges)
        if (!$categories) {
            throw new \Exception('No categories found.');
        }

<<<<<<< HEAD
        $categoryData = [];
        foreach ($categories as $category) {
            $categoryData[] = $this->formatCategory($category);
=======
        // Map each category to return only the needed fields using getters
        $categoryData = [];
        foreach ($categories as $category) {
            $categoryData[] = [
                'id' => $category->getId(),
                'name' => $category->getName()
            ];
>>>>>>> 9af87b1 (visualchanges)
        }

        return $categoryData;
    }
<<<<<<< HEAD

    /**
     * Format category data.
     *
     * @param Category|null $category
     * @return array|null
     */
    private function formatCategory(?Category $category): ?array
    {
        if (!$category) {
            return null;
        }

        return [
            'id' => $category->getId(),
            'name' => $category->getName(),
        ];
    }
=======
>>>>>>> 9af87b1 (visualchanges)
}

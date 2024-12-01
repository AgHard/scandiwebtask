<?php
namespace App\GraphQL\Resolvers;

use Doctrine\ORM\EntityManager;
use App\Entity\BaseCategory;

class CategoryResolver
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function resolveCategories()
    {
        $categories = $this->entityManager->getRepository(BaseCategory::class)->findAll();
        
        if (!$categories) {
            throw new \Exception('No categories found.');
        }

        return array_map(function (BaseCategory $category) {
            return [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'type' => $category->getCategoryType(), // Polymorphic behavior
            ];
        }, $categories);
    }
}

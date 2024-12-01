<?php
namespace App\GraphQL\Resolvers;

use Doctrine\ORM\EntityManager;
use App\Entity\BaseAttribute;

class AttributeResolver
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function resolveAttributesByProduct($productId)
    {
        $attributes = $this->entityManager->getRepository(BaseAttribute::class)->findBy(['product' => $productId]);

        if (!$attributes) {
            throw new \Exception('No attributes found for product ID: ' . $productId);
        }

        return array_map(function (BaseAttribute $attribute) {
            return [
                'id' => $attribute->getId(),
                'name' => $attribute->getName(),
                'details' => $attribute->getAttributeDetails(), // Polymorphic behavior
                'items' => $this->resolveItems($attribute),
            ];
        }, $attributes);
    }

    private function resolveItems(BaseAttribute $attribute)
    {
        $items = $this->entityManager->getRepository('App\Entity\AttributeItem')->findBy(['attribute' => $attribute->getId()]);

        if (!$items) {
            return [];
        }

        return array_map(function ($item) {
            return [
                'id' => $item->getId(),
                'display_value' => $item->getDisplayValue(),
                'value' => $item->getValue(),
            ];
        }, $items);
    }
}

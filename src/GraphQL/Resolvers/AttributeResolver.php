<?php
// src/GraphQL/Resolvers/AttributeResolver.php

namespace App\GraphQL\Resolvers;

use Doctrine\ORM\EntityManager;
use App\Entity\Attribute;
use App\Entity\AttributeItem;

class AttributeResolver
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function resolveAttributes()
    {
        $attributes = $this->entityManager->getRepository(Attribute::class)->findAll();

        if (!$attributes) {
            throw new \Exception('No attributes found.');
        }

        $attributeData = [];
        foreach ($attributes as $attribute) {
            $attributeData[] = [
                'id' => $attribute->getId(),
                'name' => $attribute->getName(),
                'type' => $attribute->getType(),
                'items' => $this->resolveItems($attribute), // Include items
            ];
        }

        return $attributeData;
    }

    public function resolveAttributesByProduct($productId)
    {
        $attributes = $this->entityManager->getRepository(Attribute::class)->findBy(['product' => $productId]);

        if (!$attributes) {
            throw new \Exception('No attributes found for product ID: ' . $productId);
        }

        $attributeData = [];
        foreach ($attributes as $attribute) {
            $attributeData[] = [
                'id' => $attribute->getId(),
                'name' => $attribute->getName(),
                'type' => $attribute->getType(),
                'items' => $this->resolveItems($attribute), // Include items
            ];
        }

        return $attributeData;
    }

    private function resolveItems(Attribute $attribute)
    {
        // Fetch AttributeItems related to this attribute
        $items = $this->entityManager->getRepository(AttributeItem::class)->findBy(['attribute' => $attribute->getId()]);

        if (!$items) {
            return [];
        }

        $itemData = [];
        foreach ($items as $item) {
            $itemData[] = [
                'id' => $item->getId(),
                'display_value' => $item->getDisplayValue(),
                'value' => $item->getValue(),
            ];
        }

        return $itemData;
    }
}

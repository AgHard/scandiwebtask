<?php
// src/GraphQL/Resolvers/AttributeItemResolver.php

namespace App\GraphQL\Resolvers;

use Doctrine\ORM\EntityManager;
use App\Entity\BaseAttributeItem;

class AttributeItemResolver
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function resolveAttributeItems()
    {
        // Fetch all attribute items from the database
        $attributeItems = $this->entityManager->getRepository(BaseAttributeItem::class)->findAll();

        if (!$attributeItems) {
            throw new \Exception('No attribute items found.');
        }

        // Map attribute items to return only the needed fields using getters
        $attributeItemData = [];
        foreach ($attributeItems as $item) {
            $attributeItemData[] = [
                'id' => $item->getId(),
                'display_value' => $item->getDisplayValue(),
                'value' => $item->getValue(),
                'attribute' => $item->getAttribute()->getId() // Return the ID of the related attribute
            ];
        }

        return $attributeItemData;
    }

    public function resolveAttributeItemsByAttribute($attributeId)
    {
        // Fetch attribute items by attribute ID
        $attributeItems = $this->entityManager->getRepository(BaseAttributeItem::class)->findBy(['attribute' => $attributeId]);

        if (!$attributeItems) {
            throw new \Exception('No attribute items found for attribute ID: ' . $attributeId);
        }

        // Map attribute items to return only the needed fields using getters
        $attributeItemData = [];
        foreach ($attributeItems as $item) {
            $attributeItemData[] = [
                'id' => $item->getId(),
                'display_value' => $item->getItemDetails(),
                'value' => $item->getValue(),
                'attribute' => $item->getAttribute()->getId() // Return the ID of the related attribute
            ];
        }

        return $attributeItemData;
    }
}

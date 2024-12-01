<?php
// src/GraphQL/Resolvers/PriceResolver.php

namespace App\GraphQL\Resolvers;

use Doctrine\ORM\EntityManager;
use App\Entity\BasePrice;

class PriceResolver
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function resolvePricesByProduct($productId)
    {
        // Fetch prices for a specific product by its ID
        $prices = $this->entityManager->getRepository(BasePrice::class)->findBy(['product' => $productId]);

        if (!$prices) {
            throw new \Exception('No prices found for product ID: ' . $productId);
        }

        // Map prices to the expected format
        $priceData = [];
        foreach ($prices as $price) {
            $priceData[] = [
                'id' => $price->getId(),
                'amount' => $price->getAmount(),
                'details' => $price->getPriceDetails(), // Polymorphic behavior
            ];
        }

        return $priceData;
    }
}

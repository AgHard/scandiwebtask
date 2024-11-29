<?php
// src/GraphQL/Resolvers/PriceResolver.php

namespace App\GraphQL\Resolvers;

use Doctrine\ORM\EntityManager;
use App\Entity\Price;

class PriceResolver
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function resolvePrices()
    {
        // Fetch all prices from the database
        $prices = $this->entityManager->getRepository(Price::class)->findAll();

        if (!$prices) {
            throw new \Exception('No prices found.');
        }

        // Map prices to the expected format
        $priceData = [];
        foreach ($prices as $price) {
            $priceData[] = [
                'id' => $price->getId(),
                'amount' => $price->getAmount(),
                'currencyLabel' => $price->getCurrencyLabel(),
                'currencySymbol' => $price->getCurrencySymbol(),
                'product' => $price->getProduct()->getId() // Return the product ID
            ];
        }

        return $priceData;
    }

    public function resolvePricesByProduct($productId)
    {
        // Fetch prices for a specific product by its ID
        $prices = $this->entityManager->getRepository(Price::class)->findBy(['product' => $productId]);

        if (!$prices) {
            throw new \Exception('No prices found for product ID: ' . $productId);
        }

        // Map prices to the expected format
        $priceData = [];
        foreach ($prices as $price) {
            $priceData[] = [
                'id' => $price->getId(),
                'amount' => $price->getAmount(),
                'currencyLabel' => $price->getCurrencyLabel(),
                'currencySymbol' => $price->getCurrencySymbol(),
                'product' => $price->getProduct()->getId() // Return the product ID
            ];
        }

        return $priceData;
    }
}

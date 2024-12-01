<?php
// src/GraphQL/Resolvers/OrderResolver.php

namespace App\GraphQL\Resolvers;

use Doctrine\ORM\EntityManager;
use App\Entity\BaseOrder;

class OrderResolver
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function resolveOrders()
    {
        $orders = $this->entityManager->getRepository(BaseOrder::class)->findAll();

        if (!$orders) {
            throw new \Exception('No orders found.');
        }

        return array_map([$this, 'formatOrder'], $orders);
    }

    private function formatOrder(BaseOrder $order)
    {
        return [
            'id' => $order->getId(),
            'totalAmount' => $order->getTotalAmount(),
            'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
            'details' => $order->getOrderDetails(), // Polymorphic call
            'products' => $this->resolveProductsByOrder($order),
        ];
    }

    private function resolveProductsByOrder(BaseOrder $order)
    {
        return array_map(function ($product) {
            return [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
            ];
        }, $order->getProducts()->toArray()); // Fix: Convert Collection to array
    }


}

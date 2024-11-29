<?php
// src/GraphQL/Resolvers/OrderResolver.php

namespace App\GraphQL\Resolvers;

use Doctrine\ORM\EntityManager;
use App\Entity\Order;

class OrderResolver
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function resolveOrders()
    {
        // Fetch all orders from the database
        $orders = $this->entityManager->getRepository(Order::class)->findAll();
        
        if (!$orders) {
            throw new \Exception('No orders found.');
        }

        // Return formatted orders
        return $this->formatOrders($orders);
    }

    public function resolveOrderById($orderId)
    {
        // Fetch order by ID
        $order = $this->entityManager->getRepository(Order::class)->find($orderId);

        if (!$order) {
            throw new \Exception('Order not found with ID: ' . $orderId);
        }

        // Return formatted order
        return $this->formatOrder($order);
    }

    private function formatOrders($orders)
    {
        $orderData = [];
        foreach ($orders as $order) {
            $orderData[] = $this->formatOrder($order);
        }
        return $orderData;
    }

    private function formatOrder(Order $order)
    {
        return [
            'id' => $order->getId(),
            'totalAmount' => $order->getTotalAmount(),
            'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
            'products' => $this->resolveProductsByOrder($order)
        ];
    }

    private function resolveProductsByOrder(Order $order)
    {
        $products = $order->getProducts();
        $productData = [];
        
        foreach ($products as $product) {
            $productData[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'inStock' => $product->getInStock(),
                'category' => $product->getCategory(),
            ];
        }

        return $productData;
    }
}

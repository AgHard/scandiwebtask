<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManager;

class PlaceOrderMutation
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function resolve($root, $args)
    {
        try {
            $order = new Order();
            $totalAmount = 0;

            foreach ($args['cartItems'] as $item) {
                $product = $this->entityManager->getRepository(Product::class)->find($item['productId']);

                if (!$product) {
                    throw new \Exception('Product not found with ID: ' . $item['productId']);
                }

                // Check if product has prices
                $prices = $product->getPrices();
                if (count($prices) === 0) {
                    throw new \Exception('No prices found for product: ' . $product->getId());
                }

                // Use the first price as an example
                $price = $prices[0];

                $order->addProduct($product);
                $totalAmount += $price->getAmount() * $item['quantity'];
            }

            $order->setTotalAmount($totalAmount);
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return [
                'success' => true,
                'message' => 'Order placed successfully',
                'orderId' => $order->getId()
            ];

        } catch (\Exception $e) {
            file_put_contents('error_log.log', 'Error in placeOrder mutation: ' . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n", FILE_APPEND);

            return [
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage()
            ];
        }
    }
}

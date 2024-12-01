<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\Type;
use App\Entity\BaseOrder;
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
            // Create a new Order instance
            $order = new BaseOrder(); // Polymorphism allows other types of orders in the future
            $totalAmount = 0;

            foreach ($args['cartItems'] as $item) {
                $product = $this->entityManager->getRepository(Product::class)->find($item['productId']);

                if (!$product) {
                    throw new \Exception('Product not found with ID: ' . $item['productId']);
                }

                // Check if the product has prices
                $prices = $product->getPrices();
                if (count($prices) === 0) {
                    throw new \Exception('No prices found for product: ' . $product->getId());
                }

                // Use the first price to calculate the total (Polymorphic call for future enhancements)
                $price = $prices[0];
                $totalAmount += $price->getAmount() * $item['quantity'];

                // Add the product to the order
                $order->addProduct($product);
            }

            $order->setTotalAmount($totalAmount);
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return [
                'success' => true,
                'message' => 'Order placed successfully',
                'orderId' => $order->getId(),
            ];
        } catch (\Exception $e) {
            file_put_contents(
                'error_log.log',
                'Error in placeOrder mutation: ' . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n",
                FILE_APPEND
            );

            return [
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage(),
            ];
        }
    }
}

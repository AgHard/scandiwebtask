<?php

namespace App\GraphQL\Mutations;

<<<<<<< HEAD
=======
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
>>>>>>> 9af87b1 (visualchanges)
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManager;

<<<<<<< HEAD
class PlaceOrderMutation implements Mutation
=======
class PlaceOrderMutation
>>>>>>> 9af87b1 (visualchanges)
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

<<<<<<< HEAD
    public function execute(array $args): array
=======
    public function resolve($root, $args)
>>>>>>> 9af87b1 (visualchanges)
    {
        try {
            $order = new Order();
            $totalAmount = 0;

            foreach ($args['cartItems'] as $item) {
                $product = $this->entityManager->getRepository(Product::class)->find($item['productId']);

                if (!$product) {
                    throw new \Exception('Product not found with ID: ' . $item['productId']);
                }

<<<<<<< HEAD
=======
                // Check if product has prices
>>>>>>> 9af87b1 (visualchanges)
                $prices = $product->getPrices();
                if (count($prices) === 0) {
                    throw new \Exception('No prices found for product: ' . $product->getId());
                }

<<<<<<< HEAD
                $price = $prices[0];
=======
                // Use the first price as an example
                $price = $prices[0];

>>>>>>> 9af87b1 (visualchanges)
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
<<<<<<< HEAD
        } catch (\Exception $e) {
=======

        } catch (\Exception $e) {
            file_put_contents('error_log.log', 'Error in placeOrder mutation: ' . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n", FILE_APPEND);

>>>>>>> 9af87b1 (visualchanges)
            return [
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage()
            ];
        }
    }
}

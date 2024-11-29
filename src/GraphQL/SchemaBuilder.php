<?php
// src/GraphQL/SchemaBuilder.php
namespace App\GraphQL;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;
use App\GraphQL\Types\CategoryType;
use App\GraphQL\Resolvers\CategoryResolver;
use App\GraphQL\Types\PriceType;
use App\GraphQL\Resolvers\PriceResolver;
use App\GraphQL\Types\GalleryType;
use App\GraphQL\Resolvers\GalleryResolver;
use App\GraphQL\Types\AttributeItemType;
use App\GraphQL\Resolvers\AttributeItemResolver;
use App\GraphQL\Types\AttributeType;
use App\GraphQL\Resolvers\AttributeResolver;
use App\GraphQL\Types\ProductType;
use App\GraphQL\Resolvers\ProductResolver;
use App\GraphQL\Types\OrderType;
use App\GraphQL\Resolvers\OrderResolver;
use App\GraphQL\Mutations\PlaceOrderMutation;
use GraphQL\Type\Definition\InputObjectType;
use Doctrine\ORM\EntityManager;

class SchemaBuilder
{
    public static function build(EntityManager $entityManager)
    {
        // Instantiate types as singletons
        $categoryType = new CategoryType();
        $attributeItemType = new AttributeItemType(); // Instantiate AttributeItemType first
        $attributeType = new AttributeType($attributeItemType); // Pass AttributeItemType as a constructor argument
        $priceType = new PriceType();
        $galleryType = new GalleryType();
        $productType = new ProductType($attributeType, $galleryType, $priceType);
        $orderType = new OrderType($productType);

        // Instantiate resolvers
        $categoryResolver = new CategoryResolver($entityManager);
        $priceResolver = new PriceResolver($entityManager);
        $galleryResolver = new GalleryResolver($entityManager);
        $attributeItemResolver = new AttributeItemResolver($entityManager);
        $attributeResolver = new AttributeResolver($entityManager);
        $productResolver = new ProductResolver($entityManager);
        $orderResolver = new OrderResolver($entityManager);
        $placeOrderMutation = new PlaceOrderMutation($entityManager);

        // Define the Query Type
        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => [
                'categories' => [
                    'type' => Type::listOf($categoryType),
                    'resolve' => function () use ($categoryResolver) {
                        return $categoryResolver->resolveCategories();
                    }
                ],
                'prices' => [
                    'type' => Type::listOf($priceType),
                    'args' => ['productId' => Type::string()],
                    'resolve' => function ($root, $args) use ($priceResolver) {
                        return $priceResolver->resolvePricesByProduct($args['productId']);
                    }
                ],
                'galleries' => [
                    'type' => Type::listOf($galleryType),
                    'args' => ['productId' => Type::string()],
                    'resolve' => function ($root, $args) use ($galleryResolver) {
                        return $galleryResolver->resolveGalleriesByProduct($args['productId']);
                    }
                ],
                'attributes' => [
                    'type' => Type::listOf($attributeType),
                    'args' => ['productId' => Type::string()],
                    'resolve' => function ($root, $args) use ($attributeResolver) {
                        return $attributeResolver->resolveAttributesByProduct($args['productId']);
                    }
                ],
                'attributeItems' => [
                    'type' => Type::listOf($attributeItemType),
                    'args' => ['attributeId' => Type::int()],
                    'resolve' => function ($root, $args) use ($attributeItemResolver) {
                        return $attributeItemResolver->resolveAttributeItemsByAttribute($args['attributeId']);
                    }
                ],
                'products' => [
                    'type' => Type::listOf($productType),
                    'args' => [
                        'id' => Type::string(),
                        'category' => Type::string(),
                    ],
                    'resolve' => function ($root, $args) use ($productResolver) {
                        if (isset($args['id'])) {
                            return [$productResolver->resolveProductById($args['id'])];
                        }
                        return $productResolver->resolveProducts($args['category'] ?? null);
                    }
                ],
                'orders' => [
                    'type' => Type::listOf($orderType),
                    'resolve' => function () use ($orderResolver) {
                        return $orderResolver->resolveOrders();
                    }
                ]
            ]
        ]);

        $mutationType = new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'placeOrder' => [
                    'type' => Type::nonNull(new ObjectType([
                        'name' => 'OrderResponse',
                        'fields' => [
                            'success' => Type::nonNull(Type::boolean()),
                            'message' => Type::string(),
                            'orderId' => Type::int(),
                        ]
                    ])),
                    'args' => [
                        'cartItems' => Type::listOf(Type::nonNull(new InputObjectType([
                            'name' => 'OrderItemInput',
                            'fields' => [
                                'productId' => Type::nonNull(Type::string()),
                                'quantity' => Type::nonNull(Type::int()),
                                'selectedSize' => Type::string(),
                                'selectedColor' => Type::string(),
                                'selectedCapacity' => Type::string(),
                                'selectedTouchID' => Type::string(),
                                'selectedUSBPorts' => Type::string(),
                            ]
                        ])))
                    ],
                    'resolve' => function ($root, $args) use ($placeOrderMutation) {
                        return $placeOrderMutation->resolve($root, $args);
                    }
                ]
            ]
        ]);

        // Build and return the schema
        return new Schema([
            'query' => $queryType,
            'mutation' => $mutationType
        ]);
    }
}

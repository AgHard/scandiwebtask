<?php
// src/GraphQL/SchemaBuilder.php
namespace App\GraphQL;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;
use App\GraphQL\Types\CategoryType;
use App\GraphQL\Types\ProductType;
use App\GraphQL\Types\OrderType;
use App\GraphQL\Types\AttributeType;
use App\GraphQL\Types\AttributeItemType;
use App\GraphQL\Types\GalleryType;
use App\GraphQL\Types\PriceType;
use App\GraphQL\Resolvers\CategoryResolver;
use App\GraphQL\Resolvers\ProductResolver;
use App\GraphQL\Resolvers\OrderResolver;
use App\GraphQL\Resolvers\AttributeResolver;
use App\GraphQL\Resolvers\AttributeItemResolver;
use App\GraphQL\Resolvers\GalleryResolver;
use App\GraphQL\Resolvers\PriceResolver;
use App\GraphQL\Mutations\BaseMutation;
use App\GraphQL\Mutations\PlaceOrderMutation;
use Doctrine\ORM\EntityManager;
use GraphQL\Type\Definition\InputObjectType;

class SchemaBuilder
{
    public static function build(EntityManager $entityManager)
    {
        // Define Types
        $categoryType = new CategoryType();
        $attributeItemType = new AttributeItemType();
        $attributeType = new AttributeType($attributeItemType);
        $priceType = new PriceType();
        $galleryType = new GalleryType();
        $productType = new ProductType($attributeType, $galleryType, $priceType);
        $orderType = new OrderType($productType);

        // Define Resolvers
        $resolvers = [
            'category' => new CategoryResolver($entityManager),
            'product' => new ProductResolver($entityManager),
            'order' => new OrderResolver($entityManager),
            'attribute' => new AttributeResolver($entityManager),
            'attributeItem' => new AttributeItemResolver($entityManager),
            'gallery' => new GalleryResolver($entityManager),
            'price' => new PriceResolver($entityManager),
        ];

        // Polymorphic Mutation
        $placeOrderMutation = new PlaceOrderMutation($entityManager);

        // Define Query Type
        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => [
                'categories' => [
                    'type' => Type::listOf($categoryType),
                    'resolve' => fn() => $resolvers['category']->resolveCategories(),
                ],
                'products' => [
                    'type' => Type::listOf($productType),
                    'args' => [
                        'id' => Type::string(),
                        'category' => Type::string(),
                    ],
                    'resolve' => fn($root, $args) =>
                        isset($args['id'])
                            ? [$resolvers['product']->resolveProductById($args['id'])]
                            : $resolvers['product']->resolveProducts($args['category'] ?? null),
                ],
                'orders' => [
                    'type' => Type::listOf($orderType),
                    'resolve' => fn() => $resolvers['order']->resolveOrders(),
                ],
                'attributes' => [
                    'type' => Type::listOf($attributeType),
                    'args' => ['productId' => Type::string()],
                    'resolve' => fn($root, $args) =>
                        $resolvers['attribute']->resolveAttributesByProduct($args['productId']),
                ],
                'attributeItems' => [
                    'type' => Type::listOf($attributeItemType),
                    'args' => ['attributeId' => Type::int()],
                    'resolve' => fn($root, $args) =>
                        $resolvers['attributeItem']->resolveAttributeItemsByAttribute($args['attributeId']),
                ],
                'galleries' => [
                    'type' => Type::listOf($galleryType),
                    'args' => ['productId' => Type::string()],
                    'resolve' => fn($root, $args) =>
                        $resolvers['gallery']->resolveGalleriesByProduct($args['productId']),
                ],
                'prices' => [
                    'type' => Type::listOf($priceType),
                    'args' => ['productId' => Type::string()],
                    'resolve' => fn($root, $args) =>
                        $resolvers['price']->resolvePricesByProduct($args['productId']),
                ],
            ],
        ]);

        // Define Mutation Type
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
                        ],
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
                            ],
                        ]))),
                    ],
                    'resolve' => fn($root, $args) => $placeOrderMutation->resolve($root, $args),
                ],
            ],
        ]);

        // Build Schema
        return new Schema([
            'query' => $queryType,
            'mutation' => $mutationType,
        ]);
    }
}

<?php
// src/GraphQL/Resolvers/ProductResolver.php

namespace App\GraphQL\Resolvers;

use Doctrine\ORM\EntityManager;
use App\Entity\Product;
use App\Entity\Price;
use App\Entity\Gallery;

<<<<<<< HEAD
class ProductResolver implements Resolver
=======
class ProductResolver
>>>>>>> 9af87b1 (visualchanges)
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

<<<<<<< HEAD
    /**
     * Resolve products based on category.
     *
     * @param array $args
     * @return array
     * @throws \Exception
     */
    public function resolve(array $args): array
    {
        $category = $args['category'] ?? null;
        return $this->resolveProducts($category);
    }

    /**
     * Resolve all products or products by category.
     *
     * @param string|null $category
     * @return array
     * @throws \Exception
     */
    public function resolveProducts($category = null): array
=======
    public function resolveProducts($category = null)
>>>>>>> 9af87b1 (visualchanges)
    {
        $criteria = [];
        if ($category !== null) {
            $criteria['category'] = $category;
        }
        $products = $this->entityManager->getRepository(Product::class)->findBy($criteria);

        if (!$products) {
            throw new \Exception('No products found.');
        }

        $productData = [];
        foreach ($products as $product) {
<<<<<<< HEAD
            $productData[] = $this->formatProduct($product);
=======
            $productData[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'inStock' => $product->getInStock(),
                'category' => $product->getCategory(),
                'prices' => $this->resolvePricesByProduct($product),
                'galleries' => $this->resolveGalleriesByProduct($product),
                'attributes' => $this->resolveAttributes($product),
                'imageUrl' => $this->resolveImageUrl($product),
            ];
>>>>>>> 9af87b1 (visualchanges)
        }

        return $productData;
    }

<<<<<<< HEAD
    /**
     * Resolve a single product by ID.
     *
     * @param array $args
     * @return array
     * @throws \Exception
     */
    public function resolveProductById(array $args): array
    {
        $productId = $args['id'];
=======
    public function resolveProductById($productId)
    {
>>>>>>> 9af87b1 (visualchanges)
        $product = $this->entityManager->getRepository(Product::class)->find($productId);

        if (!$product) {
            throw new \Exception('Product not found with ID: ' . $productId);
        }

<<<<<<< HEAD
        return $this->formatProduct($product);
    }

    /**
     * Format product data.
     *
     * @param Product $product
     * @return array
     */
    private function formatProduct(Product $product): array
    {
=======
>>>>>>> 9af87b1 (visualchanges)
        return [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'inStock' => $product->getInStock(),
            'category' => $product->getCategory(),
            'prices' => $this->resolvePricesByProduct($product),
            'galleries' => $this->resolveGalleriesByProduct($product),
            'attributes' => $this->resolveAttributes($product),
            'imageUrl' => $this->resolveImageUrl($product),
        ];
    }

<<<<<<< HEAD
    /**
     * Resolve prices associated with a product.
     *
     * @param Product $product
     * @return array
     */
    private function resolvePricesByProduct(Product $product): array
=======
    private function resolvePricesByProduct(Product $product)
>>>>>>> 9af87b1 (visualchanges)
    {
        $prices = $this->entityManager->getRepository(Price::class)->findBy(['product' => $product->getId()]);

        $priceData = [];
        foreach ($prices as $price) {
            $priceData[] = [
                'id' => $price->getId(),
                'amount' => $price->getAmount(),
                'currencyLabel' => $price->getCurrencyLabel(),
                'currencySymbol' => $price->getCurrencySymbol(),
            ];
        }

        return $priceData;
    }

<<<<<<< HEAD
    /**
     * Resolve galleries associated with a product.
     *
     * @param Product $product
     * @return array
     */
    private function resolveGalleriesByProduct(Product $product): array
=======
    private function resolveGalleriesByProduct(Product $product)
>>>>>>> 9af87b1 (visualchanges)
    {
        $galleries = $this->entityManager->getRepository(Gallery::class)->findBy(['product' => $product->getId()]);

        $galleryData = [];
        foreach ($galleries as $gallery) {
            $galleryData[] = [
                'id' => $gallery->getId(),
                'imageUrl' => $gallery->getImageUrl(),
            ];
        }

        return $galleryData;
    }

<<<<<<< HEAD
    /**
     * Resolve the primary image URL for a product.
     *
     * @param Product $product
     * @return string|null
     */
    private function resolveImageUrl(Product $product): ?string
=======
    private function resolveImageUrl(Product $product)
>>>>>>> 9af87b1 (visualchanges)
    {
        $gallery = $this->entityManager->getRepository(Gallery::class)->findOneBy(
            ['product' => $product->getId()],
            ['id' => 'ASC']
        );

        return $gallery ? $gallery->getImageUrl() : null;
    }

<<<<<<< HEAD
    /**
     * Resolve attributes associated with a product.
     *
     * @param Product $product
     * @return array
     */
    private function resolveAttributes(Product $product): array
=======
    private function resolveAttributes(Product $product)
>>>>>>> 9af87b1 (visualchanges)
    {
        $attributes = $this->entityManager->getRepository('App\Entity\Attribute')->findBy(['product' => $product->getId()]);

        $attributeData = [];
        foreach ($attributes as $attribute) {
            $attributeData[] = [
                'id' => $attribute->getId(),
                'name' => $attribute->getName(),
                'type' => $attribute->getType(),
                'items' => $this->resolveAttributeItems($attribute),
            ];
        }

        return $attributeData;
    }

<<<<<<< HEAD
    /**
     * Resolve attribute items for an attribute.
     *
     * @param $attribute
     * @return array
     */
    private function resolveAttributeItems($attribute): array
=======
    private function resolveAttributeItems($attribute)
>>>>>>> 9af87b1 (visualchanges)
    {
        $items = $this->entityManager->getRepository('App\Entity\AttributeItem')->findBy(['attribute' => $attribute->getId()]);

        $itemData = [];
        foreach ($items as $item) {
            $itemData[] = [
                'id' => $item->getId(),
                'display_value' => $item->getDisplayValue(),
                'value' => $item->getValue(),
            ];
        }

        return $itemData;
    }
}

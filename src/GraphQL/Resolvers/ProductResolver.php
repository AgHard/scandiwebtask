<?php
// src/GraphQL/Resolvers/ProductResolver.php

namespace App\GraphQL\Resolvers;

use Doctrine\ORM\EntityManager;
use App\Entity\Product;
use App\Entity\BasePrice;
use App\Entity\BaseGallery;

class ProductResolver
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function resolveProducts($category = null)
    {
        $criteria = $category !== null ? ['category' => $category] : [];
        $products = $this->entityManager->getRepository(Product::class)->findBy($criteria);

        if (!$products) {
            throw new \Exception('No products found.');
        }

        return array_map(function (Product $product) {
            return [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getProductDescription(), // Polymorphic call
                'inStock' => $product->getInStock(),
                'category' => $product->getCategory(),
                'prices' => $this->resolvePricesByProduct($product),
                'galleries' => $this->resolveGalleriesByProduct($product),
                'attributes' => $this->resolveAttributes($product),
                'imageUrl' => $this->resolveImageUrl($product),
            ];
        }, $products);
    }

    public function resolveProductById($productId)
    {
        $product = $this->entityManager->getRepository(Product::class)->find($productId);

        if (!$product) {
            throw new \Exception('Product not found with ID: ' . $productId);
        }

        return [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getProductDescription(), // Polymorphic call
            'inStock' => $product->getInStock(),
            'category' => $product->getCategory(),
            'prices' => $this->resolvePricesByProduct($product),
            'galleries' => $this->resolveGalleriesByProduct($product),
            'attributes' => $this->resolveAttributes($product),
            'imageUrl' => $this->resolveImageUrl($product),
        ];
    }

    private function resolvePricesByProduct(Product $product)
    {
        $prices = $this->entityManager->getRepository(BasePrice::class)->findBy(['product' => $product->getId()]);

        return array_map(function (BasePrice $price) {
            return [
                'id' => $price->getId(),
                'details' => $price->getPriceDetails(),
            ];
        }, $prices);
    }

    private function resolveGalleriesByProduct(Product $product)
    {
        $galleries = $this->entityManager->getRepository(BaseGallery::class)->findBy(['product' => $product->getId()]);

        return array_map(function (BaseGallery $gallery) {
            return [
                'id' => $gallery->getId(),
                'details' => $gallery->getMediaDetails(),
            ];
        }, $galleries);
    }

    private function resolveImageUrl(Product $product)
    {
        $gallery = $this->entityManager->getRepository(BaseGallery::class)->findOneBy(
            ['product' => $product->getId()],
            ['id' => 'ASC']
        );

        return $gallery ? $gallery->getMediaDetails() : null;
    }

    private function resolveAttributes(Product $product)
    {
        $attributes = $this->entityManager->getRepository('App\Entity\BaseAttribute')->findBy(['product' => $product->getId()]);

        return array_map(function ($attribute) {
            return [
                'id' => $attribute->getId(),
                'name' => $attribute->getName(),
                'details' => $attribute->getAttributeDetails(),
            ];
        }, $attributes);
    }
}

<?php
// src/GraphQL/Resolvers/ProductResolver.php

namespace App\GraphQL\Resolvers;

use Doctrine\ORM\EntityManager;
use App\Entity\Product;
use App\Entity\Price;
use App\Entity\Gallery;

class ProductResolver
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function resolveProducts($category = null)
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
        }

        return $productData;
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
            'description' => $product->getDescription(),
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

    private function resolveGalleriesByProduct(Product $product)
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

    private function resolveImageUrl(Product $product)
    {
        $gallery = $this->entityManager->getRepository(Gallery::class)->findOneBy(
            ['product' => $product->getId()],
            ['id' => 'ASC']
        );

        return $gallery ? $gallery->getImageUrl() : null;
    }

    private function resolveAttributes(Product $product)
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

    private function resolveAttributeItems($attribute)
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

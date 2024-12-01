<?php
// src/GraphQL/Resolvers/GalleryResolver.php

namespace App\GraphQL\Resolvers;

use Doctrine\ORM\EntityManager;
use App\Entity\BaseGallery;

class GalleryResolver
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function resolveGalleriesByProduct($productId)
    {
        // Fetch galleries by product ID
        $galleries = $this->entityManager->getRepository(BaseGallery::class)->findBy(['product' => $productId]);

        if (!$galleries) {
            throw new \Exception('No galleries found for product ID: ' . $productId);
        }

        return array_map(function (BaseGallery $gallery) {
            return [
                'id' => $gallery->getId(),
                'details' => $gallery->getMediaDetails(), // Polymorphic call
                'product' => $gallery->getProduct()->getId(),
            ];
        }, $galleries);
    }
}

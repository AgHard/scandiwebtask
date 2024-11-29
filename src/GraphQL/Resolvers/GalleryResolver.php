<?php
// src/GraphQL/Resolvers/GalleryResolver.php

namespace App\GraphQL\Resolvers;

use Doctrine\ORM\EntityManager;
use App\Entity\Gallery;

class GalleryResolver
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function resolveGalleries()
    {
        // Fetch all galleries from the database
        $galleries = $this->entityManager->getRepository(Gallery::class)->findAll();

        if (!$galleries) {
            throw new \Exception('No galleries found.');
        }

        // Map galleries to the expected format
        $galleryData = [];
        foreach ($galleries as $gallery) {
            $galleryData[] = [
                'id' => $gallery->getId(),
                'imageUrl' => $gallery->getImageUrl(),
                'product' => $gallery->getProduct()->getId() // Return product ID
            ];
        }

        return $galleryData;
    }

    public function resolveGalleriesByProduct($productId)
    {
        // Fetch galleries by product ID
        $galleries = $this->entityManager->getRepository(Gallery::class)->findBy(['product' => $productId]);

        if (!$galleries) {
            throw new \Exception('No galleries found for product ID: ' . $productId);
        }

        // Map galleries to the expected format
        $galleryData = [];
        foreach ($galleries as $gallery) {
            $galleryData[] = [
                'id' => $gallery->getId(),
                'imageUrl' => $gallery->getImageUrl(),
                'product' => $gallery->getProduct()->getId() // Return product ID
            ];
        }

        return $galleryData;
    }
}

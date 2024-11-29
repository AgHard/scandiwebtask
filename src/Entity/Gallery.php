<?php
// src/Entity/Gallery.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="image_galleries")
 */
class ImageGallery extends BaseGallery
{
    /**
     * @ORM\Column(name="image_url", type="string")
     */
    private $imageUrl;

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function getMediaDetails(): string
    {
        return "Image URL: " . $this->imageUrl;
    }
}

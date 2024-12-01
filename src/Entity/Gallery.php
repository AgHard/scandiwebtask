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
    public function getMediaDetails(): string
    {
        return "Image URL: " . $this->imageUrl;
    }
}

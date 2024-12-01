<?php
// src/Entity/TechProduct.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tech_products")
 */
class TechProduct extends Product
{
    public function getProductDescription(): string
    {
        return "Tech product description: " . $this->description;
    }
}

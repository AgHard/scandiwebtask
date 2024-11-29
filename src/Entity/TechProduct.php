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
    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $specs;

    public function getProductDescription(): string
    {
        return "Tech product description: " . $this->description;
    }

    public function getSpecs(): ?array
    {
        return $this->specs;
    }

    public function setSpecs(?array $specs): self
    {
        $this->specs = $specs;
        return $this;
    }
}

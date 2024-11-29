<?php
// src/Entity/ClothingProduct.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="clothing_products")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="product_type", type="string")
 */
class ClothingProduct extends Product
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $size;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $material;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $color;

    // Implementing the abstract method
    public function getProductDescription(): string
    {
        return "Clothing product description: " . $this->description . " in size " . $this->size . ", material: " . $this->material . ", color: " . $this->color;
    }

    // Getters and setters for size, material, and color
    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function getMaterial(): ?string
    {
        return $this->material;
    }

    public function setMaterial(?string $material): self
    {
        $this->material = $material;
        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;
        return $this;
    }
}

<?php
// src/Entity/Product.php

namespace App\Entity;

use App\Entity\Price;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="products_base")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $inStock;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="string")
     */
    private $category;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $brand;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $productType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Price", mappedBy="product")
     */
    protected $prices;

    public function __construct()
    {
        $this->prices = new ArrayCollection();
    }

    // Common methods
    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getInStock(): ?bool
    {
        return $this->inStock;
    }

    public function setInStock(bool $inStock): self
    {
        $this->inStock = $inStock;
        return $this;
    }

    public function getDescription(): ?string
    {
        // Temporary stripping of special characters for debugging
        return preg_replace('/[^(\x20-\x7F)]*/', '', $this->description);
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->productType;
    }

    public function setType(?string $productType): self
    {
        $this->productType = $productType;
        return $this;
    }
    /**
     * Get prices for the product
     *
     * @return Collection|Price[]
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    // Abstract method for product-specific description
    public function getProductDescription(): string
    {
        return "";
    }
}

<?php
// src/Entity/Order.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="orders")
 */
class Order extends BaseOrder
{
    /**
     * @ORM\ManyToMany(targetEntity="Product", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="orders_products",
     *      joinColumns={@ORM\JoinColumn(name="order_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")}
     * )
     */
    private $products;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->products = new ArrayCollection();
    }

    /**
     * Returns the order details as a string.
     */
    public function getOrderDetails(): string
    {
        return "Order #" . $this->id . " with total amount: " . $this->totalAmount;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * Add a product to the order.
     */
    public function addProduct(Product $product): void
    {
        $this->products[] = $product;
    }
}
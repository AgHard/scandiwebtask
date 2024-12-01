<?php
// src/Entity/BaseOrder.php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * @ORM\MappedSuperclass
 */
abstract class BaseOrder
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="float")
     */
    protected $totalAmount;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * Abstract method to retrieve order details.
     */
    abstract public function getOrderDetails(): string;

    /**
     * Abstract method to retrieve associated products.
     * @return Collection|Product[]
     */
    abstract public function getProducts(): Collection;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $amount): void
    {
        $this->totalAmount = $amount;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
<?php
// src/Entity/Order.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="orders")
 */
class Order extends BaseOrder
{
    public function getOrderDetails(): string
    {
        return "Order #" . $this->id . " with total amount: " . $this->totalAmount;
    }
}
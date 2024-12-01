<?php
// src/Entity/BasePriceClass.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="base_prices")
 */
class BasePriceClass extends BasePrice
{
    public function getPriceDetails(): string
    {
        return "Base price: " . $this->amount;
    }
}
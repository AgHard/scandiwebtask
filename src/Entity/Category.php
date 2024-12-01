<?php
// src/Entity/Category.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */
class Category extends BaseCategory
{
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isParent;

    public function getCategoryType(): string
    {
        return $this->isParent ? "Parent Category" : "Child Category";
    }
}

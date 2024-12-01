<?php
// src/Entity/AttributeItem.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="attribute_items")
 */
class TextAttributeItem extends BaseAttributeItem
{
    public function getItemDetails(): string
    {
        return "Text Attribute Item: " . $this->detail;
    }
}
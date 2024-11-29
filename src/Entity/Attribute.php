<?php
// src/Entity/Attribute.php

namespace App\Entity;

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="attributes_base")
 */
class Attribute extends BaseAttribute
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $value;

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getAttributeDetails(): string
    {
        return "Attribute: " . $this->name . " with value " . $this->value;
    }
}

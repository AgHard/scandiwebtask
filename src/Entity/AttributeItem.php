<?php
// src/Entity/AttributeItem.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="attribute_items")
 */
class AttributeItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BaseAttribute")
     * @ORM\JoinColumn(name="attribute_id", referencedColumnName="id")
     */
    private $attribute;

    /**
     * @ORM\Column(type="string")
     */
    private $detail;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttribute(): ?BaseAttribute
    {
        return $this->attribute;
    }

    public function setAttribute(?BaseAttribute $attribute): self
    {
        $this->attribute = $attribute;
        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): self
    {
        $this->detail = $detail;
        return $this;
    }
}

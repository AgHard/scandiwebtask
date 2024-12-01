<?php
// src/Entity/BaseAttributeItem.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class BaseAttributeItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $detail;

    abstract public function getItemDetails(): string;

    public function getId(): ?int
    {
        return $this->id;
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

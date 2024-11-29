<?php
// src/Entity/Category.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    // Getter for id
    public function getId(): ?int
    {
        return $this->id;
    }

    // Setter for id
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    // Getter for name
    public function getName(): ?string
    {
        return $this->name;
    }

    // Setter for name
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}

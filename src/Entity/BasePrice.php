<?php
// src/Entity/BasePrice.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class BasePrice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     */
    protected $product;

    /**
     * @ORM\Column(name="amount", type="decimal", scale=2)
     */
    protected $amount;

    /**
     * @ORM\Column(name="currency_label", type="string", length=50)
     */
    private $currencyLabel;

    /**
     * @ORM\Column(name="currency_symbol", type="string", length=5)
     */
    private $currencySymbol;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function getCurrencyLabel(): ?string
    {
        return $this->currencyLabel;
    }

    public function setCurrencyLabel(string $currencyLabel): self
    {
        $this->currencyLabel = $currencyLabel;
        return $this;
    }

    public function getCurrencySymbol(): ?string
    {
        return $this->currencySymbol;
    }

    public function setCurrencySymbol(string $currencySymbol): self
    {
        $this->currencySymbol = $currencySymbol;
        return $this;
    }

    // Abstract method for price-specific details
    abstract public function getPriceDetails(): string;
}

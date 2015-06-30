<?php

namespace Omnipay\Csob;

class CartItem
{
    /** @var string */
    private $name;

    /** @var int */
    private $quantity;

    /** @var int */
    private $amount;

    /** @var string */
    private $description;

    function __construct($name, $quantity, $amount, $description = null)
    {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->amount = $amount;
        $this->description = $description;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function toArray()
    {
        return [
            'name' => (string)$this->getName(),
            'quantity' => $this->getQuantity(),
            'amount' => $this->getAmount(),
            'description' => (string)$this->getDescription(),
        ];
    }

}
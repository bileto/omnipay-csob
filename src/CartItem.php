<?php

namespace Omnipay\Csob;

/**
 * Cart item entity
 * @package Omnipay\Csob
 * @see https://github.com/csob/paymentgateway/wiki/eAPI-v1-(English-version)#cart-items
 */
class CartItem
{
    /**
     * Item’s name, maximum length 20 characters
     *
     * @var string
     */
    private $name;

    /**
     * Quantity, must be >=1
     *
     * @var int
     */
    private $quantity;

    /**
     * Total price for the quantity of the items in hundredths of the currency.
     * The item currency of all the requests will be automatically used as the price.
     *
     * @var int
     */
    private $amount;

    /**
     * Cart item’s description, maximum length 40 characters
     *
     * @var string
     */
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

    /**
     * @return array
     */
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
<?php

namespace Omnipay\Csob;

class Purchase
{
    private $merchantId;
    private $orderNo;
    private $dttm;
    private $payOperation = 'payment';
    private $payMethod = 'card';
    private $totalAmount;
    private $currency = 'CZK';
    private $closePayment = true;
    private $returnUrl;
    private $returnMethod = 'POST';
    private $cart = [];
    private $description;
    private $merchantData;
    private $customerId;
    private $language = 'CZ';

    function __construct($merchantId, $orderNo, $returnUrl, $description)
    {
        $this->merchantId = $merchantId;
        $this->orderNo = $orderNo;
        $this->returnUrl = $returnUrl;
        $this->description = $description;
    }

    /**
     * @param string $merchantId
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
    }

    /**
     * @param string $orderNo
     */
    public function setOrderNo($orderNo)
    {
        $this->orderNo = $orderNo;
    }

    /**
     * @param string $dttm
     */
    public function setDttm($dttm)
    {
        $this->dttm = $dttm;
    }

    /**
     * @param string $payOperation
     */
    public function setPayOperation($payOperation)
    {
        $this->payOperation = $payOperation;
    }

    /**
     * @param string $payMethod
     */
    public function setPayMethod($payMethod)
    {
        $this->payMethod = $payMethod;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @param boolean $closePayment
     */
    public function setClosePayment($closePayment)
    {
        $this->closePayment = $closePayment;
    }

    /**
     * @param string $returnUrl
     */
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
    }

    /**
     * @param string $returnMethod
     */
    public function setReturnMethod($returnMethod)
    {
        $this->returnMethod = $returnMethod;
    }

    /**
     * @param array $cart
     */
    public function setCart(array $cartItems)
    {
        $this->totalAmount = 0;
        $this->cart = [];
        /** @var CartItem $cartItem */
        foreach ($cartItems as $cartItem) {
            $this->addCartItem($cartItem);
        }
    }

    /**
     * @param CartItem $cartItem
     */
    public function addCartItem(CartItem $cartItem)
    {
        $this->cart[] = $cartItem;
        $this->totalAmount += $cartItem->getAmount();
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param string $merchantData
     */
    public function setMerchantData($merchantData)
    {
        $this->merchantData = $merchantData;
    }

    /**
     * @param string $customerId
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @return string
     */
    public function getOrderNo()
    {
        return $this->orderNo;
    }

    /**
     * @return string
     */
    public function getDttm()
    {
        return $this->dttm;
    }

    /**
     * @return string
     */
    public function getPayOperation()
    {
        return $this->payOperation;
    }

    /**
     * @return string
     */
    public function getPayMethod()
    {
        return $this->payMethod;
    }

    /**
     * @return int
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return boolean
     */
    public function isClosePayment()
    {
        return $this->closePayment;
    }

    /**
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * @return string
     */
    public function getReturnMethod()
    {
        return $this->returnMethod;
    }

    /**
     * @return array
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getMerchantData()
    {
        return $this->merchantData;
    }

    /**
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = [
            "merchantId" => (string)$this->getMerchantId(),
            "orderNo" => (string)$this->getOrderNo(),
            "dttm" => (string)$this->getDttm(),
            "payOperation" => (string)$this->getPayOperation(),
            "payMethod" => (string)$this->getPayMethod(),
            "totalAmount" => $this->getTotalAmount(),
            "currency" => (string)$this->getCurrency(),
            "closePayment" => $this->isClosePayment(),
            "returnUrl" => (string)$this->getReturnUrl(),
            "returnMethod" => (string)$this->getReturnMethod(),
            "cart" => [],
            "description" => (string)$this->getDescription(),
            "merchantData" => (string)$this->getMerchantData(),
            "customerId" => (string)$this->getCustomerId(),
            "language" => (string)$this->getLanguage(),
        ];

        /** @var CartItem $cartItem */
        foreach ($this->getCart() as $cartItem) {
            $export = $cartItem->toArray();
            $data['cart'][] = $export;
        }

        if ($this->getDttm() === null) {
            $data['dttm'] = $this->generateDttm();
        }

        return $data;
    }

    /**
     * @return string
     */
    protected function generateDttm()
    {
        // should be private, but has to be protected for mocking
        return date('Ymdhis');
    }
}
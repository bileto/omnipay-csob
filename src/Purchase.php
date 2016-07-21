<?php

namespace Omnipay\Csob;

/**
 * Entity for payment init step
 * @package Omnipay\Csob
 * @see https://github.com/csob/paymentgateway/wiki/eAPI-v1-(English-version)#payment-init-operation
 */
class Purchase
{
    const CURRENCY_CZK = 'CZK';
    const CURRENCY_EUR = 'EUR';
    const CURRENCY_USD = 'USD';
    const CURRENCY_GBP = 'GBP';

    const LANG_CZ = 'CZ';
    const LANG_EN = 'EN';
    const LANG_DE = 'DE';
    const LANG_SK = 'SK';

    const PAY_OPERATION_PAYMENT = 'payment';
    const PAY_METHOD_CARD = 'card';

    /**
     * Merchant’s ID assigned by the payment gateway
     *
     * @var string
     */
    private $merchantId;

    /**
     * Reference number of the order used to match payments.
     * The number will also be indicated on the bank statement.
     * A numeric value, 10 digits max.
     *
     * @var string
     */
    private $orderNo;

    /**
     * Date and time of sending the request in the YYYYMMDDHHMMSS format
     *
     * @var string
     */
    private $dttm;

    /**
     * Type of payment operation.
     * Approved values: payment
     *
     * @var string
     */
    private $payOperation = self::PAY_OPERATION_PAYMENT;

    /**
     * Type of implicit payment method to be offered to the customer.
     * Approved values: card
     *
     * @var string
     */
    private $payMethod = self::PAY_METHOD_CARD;

    /**
     * Total amount in hundredths of the basic currency.
     * This value will appear on the payment gateway as the total amount to be paid
     *
     * @var float
     */
    private $totalAmount;

    /**
     * Currency code.
     * Approved values: CZK, EUR, USD, GBP
     *
     * @var string
     */
    private $currency = self::CURRENCY_CZK;

    /**
     * It indicates whether the payment should automatically be put in the queue
     * for settlement and paid.
     *
     * @var bool
     */
    private $closePayment = true;

    /**
     * URL to which the customer will be redirected after the payment has
     * been completed. Maximum length is 300 characters.
     *
     * @see https://github.com/csob/paymentgateway/wiki/eAPI-v1-(English-version)#return-params
     * @var string
     */
    private $returnUrl;

    /**
     * The return method to e-shop’s URL.
     * Approved values POST, GET.
     * Recommended method is POST
     *
     * @var string
     */
    private $returnMethod = 'POST';

    /**
     * A list of items to be displayed on the payment gateway.
     *
     * @var CartItem[]
     */
    private $cart = [];

    /**
     * Brief description of the purchase for 3DS page:
     * In case of customer verification on the issuing bank’s side, the
     * detail of the cart cannot be displayed as it is possible on the
     * payment gateway. Therefore, a brief description is sent to the bank.
     * Maximum length is 255 characters
     *
     * @var string
     */
    private $description;

    /**
     * Any additional data which are returned in the redirect from the payment gateway
     * to the merchant’s page. Such data may be used to keep continuity of the process
     * in the e-shop, they must be BASE64 encoded.
     * Maximum length for encoding is 255 characters
     *
     * @var string
     */
    private $merchantData;

    /**
     * Unique customer ID assigned by the e-shop. It is used if the customer’s card
     * is stored and used again in the next visit of the e-shop
     * Maximum length is 50 characters.
     *
     * @var string
     */
    private $customerId;

    /**
     * Preferred language mutation to be displayed on the payment gateway.
     * Czech mutation is by default.
     * Approved values: CZ, EN, DE, SK
     *
     * @var string
     */
    private $language = self::LANG_CZ;

    /**
     * @param string $merchantId
     * @param string $orderNo
     * @param string $returnUrl
     * @param string $description
     */
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
     * In version v1, at least 1 item (e.g. “credit charge”) and at most 2 items
     * must be in the cart (e.g. “purchase for my shop” and “shipment costs”).
     * The limit is caused by the graphic layout of the payment gateway, in another
     * version the limit will be much higher.
     *
     * @param CartItem[] $cart
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
     * In version v1, at least 1 item (e.g. “credit charge”) and at most 2 items
     * must be in the cart (e.g. “purchase for my shop” and “shipment costs”).
     * The limit is caused by the graphic layout of the payment gateway, in another
     * version the limit will be much higher.
     *
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
     * @return CartItem[]
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
        // should be private, but has to be protected because of mocking
        return date('Ymdhis');
    }
}
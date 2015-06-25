<?php

use Omnipay\Csob\Sign\Preparer;

class PreparerTest extends PHPUnit_Framework_TestCase
{
    public function testGetDataToSign()
    {
        $preparer = new Preparer();
        $data = [
            "merchantId" => "A1029DTmM7",
            "orderNo" => "1234560",
            "dttm" => "20150624090323",
            "payOperation" => "payment",
            "payMethod" => "card",
            "totalAmount" => 100,
            "currency" => "CZK",
            "closePayment" => "false",
            "returnUrl" => "https://vasobchod.cz/gateway-return",
            "returnMethod" => "POST",
            "cart" => [
                [
                    "name" => "Shopping at ...",
                    "quantity" => 1,
                    "amount" => 100,
                    "description" => "Lenovo ThinkPad Edge E540..."
                ],
                [
                    "name" => "Shipping",
                    "quantity" => 1,
                    "amount" => 0,
                    "description" => "PPL"
                ]
            ],
            "description" => "Nákup na vasobchod.cz (Lenovo ThinkPad Edge E540, Doprava PPL)",
            "merchantData" => null,
            "customerId" => "1234",
            "language" => "CZ",
        ];
        $keys = [
            'merchantId', 'orderNo', 'dttm', 'payOperation', 'payMethod', 'totalAmount', 'currency', 'closePayment', 'returnUrl',
            'returnMethod', 'cart', 'description', 'merchantData', 'customerId', 'language'
        ];
        $expectedStrToSign = "A1029DTmM7|1234560|20150624090323|payment|card|100|CZK|false|https://vasobchod.cz/gateway-return|POST|Shopping at ...|1|100|Lenovo ThinkPad Edge E540...|Shipping|1|0|PPL|Nákup na vasobchod.cz (Lenovo ThinkPad Edge E540, Doprava PPL)|1234|CZ";

        $strToSign = $preparer->getStringToSign($data, $keys);

        $this->assertSame($expectedStrToSign, $strToSign);
    }

    public function testGetDataToSignIgnoresNotRequestedKeys()
    {
        $preparer = new Preparer();
        $data = [
            "merchantId" => "A1029DTmM7",
            "orderNo" => "1234567",
            "totalAmount" => 100,
            "currency" => "CZK",
            "closePayment" => false,
        ];
        $keys = [
            "merchantId",
            "closePayment",
        ];
        $expectedStrToSign = "A1029DTmM7|false";

        $strToSign = $preparer->getStringToSign($data, $keys);

        $this->assertSame($expectedStrToSign, $strToSign);
    }

    public function testGetDataToSignIgnores()
    {
        $preparer = new Preparer();
        $data = [
            "merchantId" => "A1029DTmM7",
            "cart" => [
                [
                    "name" => "Shopping at Foo",
                    "quantity" => 1,
                    "amount" => 100,
                    "description" => "Lenovo ThinkPad Edge E540"
                ],
            ],
            "currency" => "CZK",
        ];
        $keys = [
            "merchantId",
            "cart",
            "currency",
        ];
        $expectedStrToSign = "A1029DTmM7|Shopping at Foo|1|100|Lenovo ThinkPad Edge E540|CZK";

        $strToSign = $preparer->getStringToSign($data, $keys);

        $this->assertSame($expectedStrToSign, $strToSign);
    }
}

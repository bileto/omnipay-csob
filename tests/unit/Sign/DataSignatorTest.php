<?php

class DataSignatorTest extends PHPUnit_Framework_TestCase {

    public function testSign()
    {
        $privateKey = file_get_contents(__DIR__ . '/assets/rsa_A1029DTmM7.key');
        $publicKey = file_get_contents(__DIR__ . '/assets/rsa_A1029DTmM7.pub');
        $preparer = new \Omnipay\Csob\Sign\Preparer();
        $signator = new \Omnipay\Csob\Sign\Signator($privateKey);
        $verifier = new \Omnipay\Csob\Sign\Verifier($publicKey);
        $signator = new \Omnipay\Csob\Sign\DataSignator($preparer, $signator);

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
        $arrayKeys = [
            'merchantId', 'orderNo', 'dttm', 'payOperation', 'payMethod', 'totalAmount', 'currency', 'closePayment', 'returnUrl',
            'returnMethod', 'cart', 'description', 'merchantData', 'customerId', 'language'
        ];
        $expectedSignature = "WaCyOg/6UikvZ3ut+B/6D7NaV1Vacj1eBzYEH19EC3Jwfe7fH2GyKliTK4dWZvTXNmnoAErRaR6+QSRmaQr12c2shyXG3XatfdYPRAjrneNnb3wgDwG/CgFAPc3xkw+9V2hVYiAP8QJtqX3dptvMWp+SjouGwP4jCUZQM9zebOkNdmsLn5QP8dj7qJ9n++AU0TG/WdImU0+RLMH4XRSp5xaebOVlzeLWXZKwPZB4EpVVlC/DEgF19t9dKMIKd+16Q9LVuRMPEvP/6zrx1EYbuGpV4Qbwdb5gSCyC3DkjB9gCRG0ZX8WftHsvVNbsFx9i4ujcg7SFK85KAsQuWTUXEw==";
        $stringToSign = $preparer->getStringToSign($data, $arrayKeys);

        $signature = $signator->sign($data, $arrayKeys);

        $this->assertSame($expectedSignature, $signature);
        $this->assertTrue($verifier->verify($stringToSign, $signature), 'Verification failed');
    }
}

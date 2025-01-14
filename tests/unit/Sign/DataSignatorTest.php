<?php

declare(strict_types=1);

use Omnipay\Csob\Sign\DataSignator;
use Omnipay\Csob\Sign\Preparer;
use Omnipay\Csob\Sign\Signator;
use Omnipay\Csob\Sign\Verifier;
use PHPUnit\Framework\TestCase;

class DataSignatorTest extends TestCase {

    public function testSign(): void
    {
        $privateKey = file_get_contents(__DIR__ . '/assets/rsa_A5478VavfP.key');
        $publicKey = file_get_contents(__DIR__ . '/assets/rsa_A5478VavfP.pub');
        $preparer = new Preparer();
        $signator = new Signator($privateKey);
        $verifier = new Verifier($publicKey);
        $signator = new DataSignator($preparer, $signator);

        $data = [
            'merchantId' => 'A5478VavfP',
            'orderNo' => '1234560',
            'dttm' => '20150624090323',
            'payOperation' => 'payment',
            'payMethod' => 'card',
            'totalAmount' => 100,
            'currency' => 'CZK',
            'closePayment' => 'false',
            'returnUrl' => 'https://vasobchod.cz/gateway-return',
            'returnMethod' => 'POST',
            'cart' => [
                [
                    'name' => 'Shopping at ...',
                    'quantity' => 1,
                    'amount' => 100,
                    'description' => 'Lenovo ThinkPad Edge E540...'
                ],
                [
                    'name' => 'Shipping',
                    'quantity' => 1,
                    'amount' => 0,
                    'description' => 'PPL'
                ]
            ],
            'merchantData' => null,
            'customerId' => '1234',
            'language' => 'cs',
        ];
        $arrayKeys = [
            'merchantId', 'orderNo', 'dttm', 'payOperation', 'payMethod', 'totalAmount', 'currency', 'closePayment', 'returnUrl',
            'returnMethod', 'cart', 'merchantData', 'customerId', 'language'
        ];
        $expectedSignature = "C2sTzf8mYaeyQv3+zyxrh0k/i07DsvgXDGLrZI7VS2c9WuSBB2Q9ZBTEZl45Km32XpvqTwC4LgzI/6jrhfWY0jDp6AsZKrbOonrJOtxeaywd7a6+scouAeve9npCEEDBwdHOrxOqxpz8Z6uGJbPaoEvN81fOPK3OhXaaA3gU9rJSYm4S5IrljG1Z+QeXIm8R+KY+GC9KoLwvpeaGWrU6xc2J3RoG+BwxVHFV3BekIrNwK/TtML0sUmJGbJPgQYEIIuOUUVaTNIGe16fDDQ2CdYjdvDcijGhvK4ZMtRCjUHnBr0Iqq5TW50XK5dFGiGiwbI5QiSwFVuqTaHz54G1Drg==";
        $stringToSign = $preparer->getStringToSign($data, $arrayKeys);

        $signature = $signator->sign($data, $arrayKeys);

        $this->assertSame($expectedSignature, $signature);
        $this->assertTrue($verifier->verify($stringToSign, $signature), 'Verification failed');
    }
}

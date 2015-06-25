<?php

class SignatorTest extends PHPUnit_Framework_TestCase
{
    public function testSign()
    {
        $privateKey = __DIR__ . '/assets/rsa_A1029DTmM7.key';
        $publicKey = __DIR__ . '/assets/rsa_A1029DTmM7.pub';
        $signator = new \Omnipay\Csob\Sign\Signator($privateKey);
        $verifier = new \Omnipay\Csob\Sign\Verifier($publicKey);
        $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec malesuada porta orci, eget vehicula tortor eleifend in.';

        $signed = $signator->sign($text);

        $this->assertNotSame($text, $signed);
        $this->assertTrue($verifier->verify($text, $signed));
    }
}

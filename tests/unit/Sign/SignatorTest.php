<?php

use Omnipay\Csob\Sign\Signator;
use Omnipay\Csob\Sign\Verifier;

class SignatorTest extends PHPUnit_Framework_TestCase
{
    public function testSign()
    {
        $privateKey = file_get_contents(__DIR__ . '/assets/rsa_A1029DTmM7.key');
        $publicKey = file_get_contents(__DIR__ . '/assets/rsa_A1029DTmM7.pub');
        $signator = new Signator($privateKey);
        $verifier = new Verifier($publicKey);
        $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec malesuada porta orci, eget vehicula tortor eleifend in.';

        $signed = $signator->sign($text);

        $this->assertNotSame($text, $signed);
        $this->assertTrue($verifier->verify($text, $signed));
    }
}

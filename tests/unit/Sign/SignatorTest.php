<?php

declare(strict_types=1);

use Omnipay\Csob\Sign\Signator;
use Omnipay\Csob\Sign\Verifier;
use PHPUnit\Framework\TestCase;

class SignatorTest extends TestCase
{
    public function testSign(): void
    {
        $privateKey = file_get_contents(__DIR__ . '/assets/rsa_A5478VavfP.key');
        $publicKey = file_get_contents(__DIR__ . '/assets/rsa_A5478VavfP.pub');
        $signator = new Signator($privateKey);
        $verifier = new Verifier($publicKey);
        $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec malesuada porta orci, eget vehicula tortor eleifend in.';

        $signed = $signator->sign($text);

        $this->assertNotSame($text, $signed);
        $this->assertTrue($verifier->verify($text, $signed));
    }
}

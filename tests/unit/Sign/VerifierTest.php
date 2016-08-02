<?php

use Omnipay\Csob\Sign\Signator;
use Omnipay\Csob\Sign\Verifier;

class VerifierTest extends PHPUnit_Framework_TestCase
{
    public function testVerify()
    {
        $privateKey = file_get_contents(__DIR__ . '/assets/rsa_A1029DTmM7.key');
        $publicKey = file_get_contents(__DIR__ . '/assets/rsa_A1029DTmM7.pub');
        $signator = new Signator($privateKey);
        $verifier = new Verifier($publicKey);
        $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus arcu, accumsan in massa non';
        $signed = $signator->sign($text);

        $this->assertTrue($verifier->verify($text, $signed));
        $this->assertFalse($verifier->verify('Other text', $signed));
        $this->assertFalse($verifier->verify($text, 'Other signed text'));
    }
}

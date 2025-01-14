<?php

declare(strict_types=1);

use Omnipay\Csob\Sign\Signator;
use Omnipay\Csob\Sign\Verifier;
use PHPUnit\Framework\TestCase;

class VerifierTest extends TestCase
{
    public function testVerify(): void
    {
        $privateKey = file_get_contents(__DIR__ . '/assets/rsa_A5478VavfP.key');
        $publicKey = file_get_contents(__DIR__ . '/assets/rsa_A5478VavfP.pub');
        $signator = new Signator($privateKey);
        $verifier = new Verifier($publicKey);
        $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus arcu, accumsan in massa non';
        $signed = $signator->sign($text);

        $this->assertTrue($verifier->verify($text, $signed), 'Failed to verify correct text and signature of correct text');
        $this->assertFalse($verifier->verify('Other text', $signed), 'Failed to verify incorrect text and signature of correct text');
        $this->assertFalse($verifier->verify($text, 'Other signed text'), 'Failed to verify correct text and unsigned text');
    }
}

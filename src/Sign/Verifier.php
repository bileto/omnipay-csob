<?php

namespace Omnipay\Csob\Sign;

class Verifier
{
    /** @var string */
    private $publicKeyFilename;

    function __construct($publicKeyFilename)
    {
        $this->publicKeyFilename = $publicKeyFilename;
    }

    /**
     * @param string $text
     * @param string $signatureBase64
     * @return bool
     */
    function verify($text, $signatureBase64) {
        $public = file_get_contents($this->publicKeyFilename);
        $publicKeyId = openssl_get_publickey($public);

        $signature = base64_decode($signatureBase64);
        $res = openssl_verify($text, $signature, $publicKeyId);
        openssl_free_key($publicKeyId);

        return $res === 1;
    }
}
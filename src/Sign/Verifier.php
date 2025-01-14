<?php

namespace Omnipay\Csob\Sign;

class Verifier
{
    /** @var string */
    private $publicKey;

    /**
     * @param string $publicKey Content of public key file
     */
    function __construct($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    /**
     * @param string $text
     * @param string $signatureBase64
     * @return bool
     */
    function verify($text, $signatureBase64) {
        $publicKeyId = openssl_pkey_get_public($this->publicKey);

        $signature = base64_decode($signatureBase64);
        $res = openssl_verify($text, $signature, $publicKeyId, OPENSSL_ALGO_SHA256);
        if ($res<1) {
            //print_r($text);
            //print_r($this->publicKey);
            while($err = openssl_error_string()) {
                print_r($err);
            }
        }
        openssl_free_key($publicKeyId);

        return $res === 1;
    }
}
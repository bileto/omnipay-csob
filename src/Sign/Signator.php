<?php

namespace Omnipay\Csob\Sign;

class Signator
{
    /** @var string */
    private $privateKey;

    /** @var string */
    private $privateKeyPassword;

    /**
     * @param string $privateKey Content of private key file
     * @param string $privateKeyPassword
     */
    function __construct($privateKey, $privateKeyPassword = null)
    {
        $this->privateKey = $privateKey;
        $this->privateKeyPassword = $privateKeyPassword;
    }

    /**
     * @param string $text
     * @return string Base64 encoded
     */
    public function sign($text) {
        $privateKeyId = openssl_get_privatekey($this->privateKey, $this->privateKeyPassword);

        openssl_sign($text, $signature, $privateKeyId, OPENSSL_ALGO_SHA256);
        $signature = base64_encode($signature);
        openssl_free_key($privateKeyId);

        return $signature;
    }
}
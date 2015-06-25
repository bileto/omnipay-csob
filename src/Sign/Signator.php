<?php

namespace Omnipay\Csob\Sign;

class Signator
{
    /** @var string */
    private $privateKeyFilename;

    /** @var string */
    private $privateKeyPassword;

    function __construct($privateKeyFilename, $privateKeyPassword = null)
    {
        $this->privateKeyFilename = $privateKeyFilename;
        $this->privateKeyPassword = $privateKeyPassword;
    }

    /**
     * @param string $text
     * @return string Base64 encoded
     */
    public function sign($text) {
        $private = file_get_contents($this->privateKeyFilename);
        $privateKeyId = openssl_get_privatekey($private, $this->privateKeyPassword);

        openssl_sign($text, $signature, $privateKeyId);
        $signature = base64_encode($signature);
        openssl_free_key($privateKeyId);

        return $signature;
    }
}
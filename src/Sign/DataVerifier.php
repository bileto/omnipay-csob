<?php

namespace Omnipay\Csob\Sign;

class DataVerifier
{
    /** @var Preparer */
    private $preparer;

    /** @var Verifier */
    private $verifier;

    function __construct(Preparer $preparer, Verifier $verifier)
    {
        $this->preparer = $preparer;
        $this->verifier = $verifier;
    }

    /**
     * @param array $data
     * @param array $arrayKeys
     * @param string $signatureBase64
     * @return bool
     */
    public function verify(array $data, array $arrayKeys, $signatureBase64)
    {
        $strToSign = $this->preparer->getStringToSign($data, $arrayKeys);
        return $this->verifier->verify($strToSign, $signatureBase64);
    }
}
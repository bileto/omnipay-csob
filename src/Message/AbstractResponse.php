<?php

namespace Omnipay\Csob\Message;

use Omnipay\Common\Message\AbstractResponse as OmnipayAbstractResponse;
use Omnipay\Csob\Sign\DataVerifier;

class AbstractResponse extends OmnipayAbstractResponse
{
    /** @var DataVerifier */
    private $verifier;

    /** @var boolean */
    private $isVerified;

    /**
     * @param DataVerifier $verifier
     */
    public function setVerifier(DataVerifier $verifier)
    {
        $this->verifier = $verifier;
    }

    public function isVerified()
    {
        if ($this->isVerified === null) {
            $data = $this->getData();
            $arrayKeys = ['payId', 'dttm', 'resultCode', 'resultMessage', 'paymentStatus', 'authCode', 'merchantData'];
            $signature = $this->getSignature();
            $this->isVerified = $this->verifier->verify($data, $arrayKeys, $signature);
        }
        return $this->isVerified;
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        if (!$this->isVerified()) {
            return false;
        }
        return $this->getCode() === 0 || $this->getCode() === "0";
    }

    public function getSignature()
    {
        if (isset($this->data['signature'])) {
            return $this->data['signature'];
        }
    }

    public function getDttm()
    {
        if (isset($this->data['dttm'])) {
            return $this->data['dttm'];
        }
    }

    public function getCode()
    {
        if (isset($this->data['resultCode'])) {
            return $this->data['resultCode'];
        }
    }

    public function getMessage()
    {
        if (!$this->isVerified()) {
            return 'Verification error, the signature cannot be verified with given public key.';
        }
        if (isset($this->data['resultMessage'])) {
            return $this->data['resultMessage'];
        }
    }
}
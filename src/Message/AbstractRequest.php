<?php

namespace Omnipay\Csob\Message;

use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;
use Omnipay\Csob\Sign\DataSignator;
use Omnipay\Csob\Sign\DataVerifier;

abstract class AbstractRequest extends OmnipayAbstractRequest
{
    /** @var DataSignator */
    private $signator;

    /** @var DataVerifier */
    private $verifier;

    /** @var string */
    private $apiUrl;

    /**
     * @param DataSignator $signator
     */
    public function setSignator(DataSignator $signator)
    {
        $this->signator = $signator;
    }

    /**
     * @return DataSignator
     */
    public function getSignator()
    {
        return $this->signator;
    }

    /**
     * @return DataVerifier
     */
    public function getVerifier()
    {
        return $this->verifier;
    }

    /**
     * @param DataVerifier $verifier
     */
    public function setVerifier(DataVerifier $verifier)
    {
        $this->verifier = $verifier;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @param string $apiUrl
     */
    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }
}
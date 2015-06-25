<?php

namespace Omnipay\Csob\Message;

use Omnipay\Csob\Sign\DataSignator;
use Omnipay\Csob\Sign\DataVerifier;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /** @var DataSignator */
    private $signator;

    /** @var DataVerifier */
    private $verifier;

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
}
<?php

namespace Omnipay\Csob;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Csob\Message\CompletePurchaseRequest;
use Omnipay\Csob\Message\InitPaymentRequest;
use Omnipay\Csob\Message\ProcessPaymentRequest;
use Omnipay\Csob\Message\PaymentResponse;
use Omnipay\Csob\Sign\DataSignator;
use Omnipay\Csob\Sign\DataVerifier;

class Gateway extends AbstractGateway
{
    /** @var DataSignator */
    private $signator;

    /** @var DataVerifier */
    private $verifier;

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'ČSOB';
    }

    /**
     * @param DataSignator $signator
     */
    public function setSignator(DataSignator $signator)
    {
        $this->signator = $signator;
    }

    /**
     * @param DataVerifier $verifier
     */
    public function setVerifier(DataVerifier $verifier)
    {
        $this->verifier = $verifier;
    }

    /**
     * @param array $parameters
     * @return InitPaymentRequest
     * @throws \Exception
     */
    public function initPayment(array $parameters = array())
    {
        return $this->createRequest(InitPaymentRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return ProcessPaymentRequest
     * @throws \Exception
     */
    public function processPayment(array $parameters = array())
    {
        return $this->createRequest(ProcessPaymentRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return InitPaymentRequest
     * @throws \Exception
     */
    public function purchase(array $parameters = array())
    {
        $initRequest = $this->initPayment($parameters);
        /** @var PaymentResponse $initResponse */
        $initResponse = $initRequest->send();
        if (!$initResponse->isSuccessful()) {
            throw new InvalidResponseException($initResponse->getMessage(), $initResponse->getCode());
        }
        $processRequest = $this->processPayment([
            'merchantId' => $initRequest->getMerchantId(),
            'payId' => $initResponse->getTransactionReference(),
            'dttm' => $initResponse->getDttm(),
        ]);
        return $processRequest;
    }

    /**
     * @param array $parameters
     * @return CompletePurchaseRequest
     * @throws \Exception
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    protected function createRequest($class, array $parameters)
    {
        if (!($this->signator instanceof DataSignator)) {
            throw new \Exception('Cannot create request, Signator is not set');
        }
        if (!($this->verifier instanceof DataVerifier)) {
            throw new \Exception('Cannot create request, Verifier is not set');
        }

        /** @var \Omnipay\Csob\Message\AbstractRequest $request */
        $request = parent::createRequest($class, $parameters);

        $request->setSignator($this->signator);
        $request->setVerifier($this->verifier);

        return $request;
    }


}
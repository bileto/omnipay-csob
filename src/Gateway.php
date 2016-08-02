<?php

namespace Omnipay\Csob;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Csob\Message\CompletePurchaseRequest;
use Omnipay\Csob\Message\EchoRequest;
use Omnipay\Csob\Message\InitPaymentRequest;
use Omnipay\Csob\Message\ProcessPaymentRequest;
use Omnipay\Csob\Message\PaymentResponse;
use Omnipay\Csob\Sign\DataSignator;
use Omnipay\Csob\Sign\DataVerifier;

/**
 * ČSOB payment gateway
 *
 * @package Omnipay\Csob
 * @see https://github.com/csob/paymentgateway/wiki/eAPI-v1.6-EN
 */
class Gateway extends AbstractGateway
{
    const URL_SANDBOX = 'https://iapi.iplatebnibrana.csob.cz/api/v1.6';
    const URL_PRODUCTION = 'https://api.platebnibrana.csob.cz/api/v1.6';

    /** @var DataSignator */
    private $signator;

    /** @var DataVerifier */
    private $verifier;

    /** @var bool */
    private $isSandbox = false;

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
     * @return boolean
     */
    public function isSandbox()
    {
        return $this->isSandbox;
    }

    /**
     * @param boolean $isSandbox
     */
    public function setSandbox($isSandbox)
    {
        $this->isSandbox = (bool)$isSandbox;
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

    /**
     * @param string $merchantId
     * @param string $httpMethod
     * @return Message\EchoResponse
     * @throws \Exception
     * @see https://github.com/csob/paymentgateway/wiki/eAPI-1.5-EN#getpost-httpsapiplatebnibranacsobczapiv15echo-
     */
    public function testGateway($merchantId, $httpMethod = 'GET')
    {
        $data = [
            'merchantId' => $merchantId,
            'dttm' => date('Ymdhis'),
        ];

        /** @var EchoRequest $request */
        $request = $this->createRequest(EchoRequest::class, $data);
        if ($httpMethod === 'POST') {
            $response = $request->sendViaPost();
        } else {
            $response = $request->sendViaGet();
        }
        return $response;
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
        $apiUrl = $this->getApiUrl();
        $request->setApiUrl($apiUrl);

        return $request;
    }

    /**
     * @return string
     */
    private function getApiUrl()
    {
        if (!$this->isSandbox()) {
            return self::URL_PRODUCTION;
        } else {
            return self::URL_SANDBOX;
        }
    }
}
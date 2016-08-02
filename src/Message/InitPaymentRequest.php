<?php

namespace Omnipay\Csob\Message;

use Guzzle\Http\Message\RequestInterface;
use Omnipay\Common\Message\ResponseInterface;

class InitPaymentRequest extends AbstractRequest
{

    public function setMerchantId($value)
    {
        $this->setParameter('merchantId', $value);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setOrderNo($value)
    {
        $this->setParameter('orderNo', $value);
    }

    public function setDttm($value)
    {
        $this->setParameter('dttm', $value);
    }

    public function setPayOperation($value)
    {
        $this->setParameter('payOperation', $value);
    }

    public function setPayMethod($value)
    {
        $this->setParameter('payMethod', $value);
    }

    public function setTotalAmount($value)
    {
        $this->setParameter('totalAmount', $value);
    }

    public function setCurrency($value)
    {
        $this->setParameter('currency', $value);
    }

    public function setClosePayment($value)
    {
        $this->setParameter('closePayment', $value);
    }

    public function setReturnUrl($value)
    {
        $this->setParameter('returnUrl', $value);
    }

    public function setReturnMethod($value)
    {
        $this->setParameter('returnMethod', $value);
    }

    public function setCart(array $value)
    {
        $this->setParameter('cart', $value);
    }

    public function setDescription($value)
    {
        $this->setParameter('description', $value);
    }

    public function setMerchantData($value)
    {
        $this->setParameter('merchantData', $value);
    }

    public function setCustomerId($value)
    {
        $this->setParameter('customerId', $value);
    }

    public function setLanguage($value)
    {
        $this->setParameter('language', $value);
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $data = $this->getParameters();
        $data['signature'] = $this->signData($data);
        return $data;
    }

    private function signData($data)
    {
        $arrayKeys = [
            'merchantId', 'orderNo', 'dttm', 'payOperation', 'payMethod', 'totalAmount', 'currency', 'closePayment', 'returnUrl',
            'returnMethod', 'cart', 'description', 'merchantData', 'customerId', 'language'
        ];
        return $this->getSignator()->sign($data, $arrayKeys);
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $apiUrl = $this->getApiUrl() . '/payment/init';
        $httpRequest = $this->httpClient->createRequest(
            RequestInterface::POST,
            $apiUrl,
            ['Content-Type' => 'application/json'],
            json_encode($data)
        );

        $httpResponse = $httpRequest->send();
        $data = $httpResponse->json();
        $response = new PaymentResponse($this, $data);
        $response->setVerifier($this->getVerifier());

        return $this->response = $response;
    }

}
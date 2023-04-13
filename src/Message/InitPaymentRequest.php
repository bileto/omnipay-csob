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

    public function setCustomer($value)
    {
        $this->setParameter('customer', $value);
    }

    public function setOrder($value)
    {
        $this->setParameter('order', $value);
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

    public function setTtlSec($value)
    {
        $this->setParameter('ttlSec', $value);
    }

    public function setLogoVersion($value)
    {
        $this->setParameter('logoVersion', $value);
    }

    public function setColorSchemeVersion($value)
    {
        $this->setParameter('colorSchemeVersion', $value);
    }

    public function setCustomExpiry($value)
    {
        $this->setParameter('customExpiry', $value);
    }

    // FIXME Remove

    /**
     * @deprecated
     */
    public function setDescription($value)
    {
        //FIXME Disabled as deprecated $this->setParameter('description', $value);
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
            'merchantId',
            'orderNo',
            'dttm',
            'payOperation',
            'payMethod',
            'totalAmount',
            'currency',
            'closePayment',
            'returnUrl',
            'returnMethod',
            'cart',
            'customer',
            'order',
            'merchantData',
            'customerId',
            'language',
            'ttlSec',
            'logoVersion',
            'colorSchemeVersion',
            'customExpiry',
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
        $stringBody = json_encode($data);
        $httpRequest = $this->httpClient->createRequest(
            RequestInterface::POST,
            $apiUrl,
            ['Content-Type' => 'application/json'],
            $stringBody
        );

        print_r($httpRequest->getRawHeaders());
        print_r($stringBody);

        $httpResponse = $httpRequest->send();

        try {
            $data = $httpResponse->json();
            $response = new PaymentResponse($this, $data);
            $response->setVerifier($this->getVerifier());

            return $this->response = $response;
        } catch (\Exception $e) {
            print_r($httpResponse->getBody(true));

            throw $e;
        }
    }
}
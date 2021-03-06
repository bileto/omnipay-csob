<?php

namespace Omnipay\Csob\Message;

class ProcessPaymentRequest extends AbstractRequest
{
    public function setDttm($value)
    {
        return $this->setParameter('dttm', $value);
    }

    public function setPayId($value)
    {
        return $this->setParameter('payId', $value);
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->getParameters();
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ProcessPaymentResponse
     */
    public function sendData($data)
    {
        $url = $this->createUri();
        $httpRequest = $this->httpClient->get(
            $url,
            null,
            array(
                'allow_redirects' => false,
            )
        );

        $httpResponse = $httpRequest->send();
        return $this->response = new ProcessPaymentResponse($this, $httpResponse->getEffectiveUrl());
    }

    private function createUri()
    {
        $uri = $this->getApiUrl() . '/payment/process';

        $signator = $this->getSignator();
        $signature = $signator->sign($this->getParameters(), ['merchantId', 'payId', 'dttm']);

        return $uri  . '/' .
            urlencode($this->getParameter('merchantId')) . '/' .
            urlencode($this->getParameter('payId')) . '/' .
            urlencode($this->getParameter('dttm')) . '/' .
            urlencode($signature);
    }

}
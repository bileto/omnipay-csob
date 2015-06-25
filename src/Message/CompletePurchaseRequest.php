<?php

namespace Omnipay\Csob\Message;

class CompletePurchaseRequest extends AbstractRequest
{
    public function setAuthCode($value)
    {
        $this->setParameter('authCode', $value);
    }

    public function setSignature($value)
    {
        $this->setParameter('signature', $value);
    }

    public function setDttm($value)
    {
        $this->setParameter('dttm', $value);
    }

    public function setResultCode($value)
    {
        $this->setParameter('resultCode', $value);
    }

    public function setPayId($value)
    {
        $this->setParameter('payId', $value);
    }

    public function setResultMessage($value)
    {
        $this->setParameter('resultMessage', $value);
    }

    public function setPaymentStatus($value)
    {
        $this->setParameter('paymentStatus', $value);
    }

    public function setMerchantData($value)
    {
        $this->setParameter('merchantData', $value);
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
     * @return PaymentResponse
     */
    public function sendData($data)
    {
        $response = new PaymentResponse($this, $data);
        $response->setVerifier($this->getVerifier());
        return $this->response = $response;
    }
}
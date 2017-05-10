<?php

namespace Omnipay\Csob\Message;

class PaymentResponse extends AbstractResponse
{
    public function getAuthCode()
    {
        if (isset($this->data['authCode'])) {
            return $this->data['authCode'];
        }
    }

    public function getTransactionReference()
    {
        if (isset($this->data['payId']) && !empty($this->data['payId'])) {
            return (string) $this->data['payId'];
        }
        return null;
    }


    public function getPaymentStatus()
    {
        if (isset($this->data['paymentStatus'])) {
            return $this->data['paymentStatus'];
        }
    }

    public function getMerchantData()
    {
        if (isset($this->data['merchantData'])) {
            return $this->data['merchantData'];
        }
    }
}
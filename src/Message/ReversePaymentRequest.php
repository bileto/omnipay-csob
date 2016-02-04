<?php
namespace Omnipay\Csob\Message;

use Guzzle\Http\Message\RequestInterface;

class ReversePaymentRequest extends AbstractRequest
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
        $data = [
            "merchantId" => $this->getParameter('merchantId'),
            "payId" => $this->getParameter('payId'),
            "dttm" => $this->getParameter('dttm'),
        ];
        $data['signature'] = $this->signData($data);
        return $data;
    }

    private function signData($data)
    {
        $arrayKeys = [
            'merchantId', 'payId', 'dttm',
        ];
        return $this->getSignator()->sign($data, $arrayKeys);
    }

    /**
     * @param mixed $data
     * @return PaymentResponse
     */
    public function sendData($data)
    {
        $apiUrl = $this->getApiUrl() . '/payment/reverse';
        $httpRequest = $this->httpClient->createRequest(
            RequestInterface::PUT,
            $apiUrl,
            ['Content-Type' => 'application/json'],
            json_encode($data)
        );

        $httpResponse = $httpRequest->send();
        $data = $httpResponse->json();
        dump($data);
        $response = new ReversePaymentResponse($this, $data);
        $response->setVerifier($this->getVerifier());

        return $this->response = $response;
    }
}
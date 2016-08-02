<?php

namespace Omnipay\Csob\Message;

use Guzzle\Http\Message\RequestInterface;
use Omnipay\Common\Message\ResponseInterface;

class EchoRequest extends AbstractRequest
{
    public function setMerchantId($value)
    {
        $this->setParameter('merchantId', $value);
    }

    public function setDttm($value)
    {
        $this->setParameter('dttm', $value);
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
            'merchantId', 'dttm',
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
        return $this->sendViaPost();
    }

    public function sendViaPost()
    {
        $apiUrl = $this->getApiUrl() . '/echo';
        $data = json_encode($this->getData());

        $httpRequest = $this->httpClient->createRequest(
            RequestInterface::POST,
            $apiUrl,
            ['Content-Type' => 'application/json'],
            $data
        );

        return $this->response = $this->sendAndProcessResponse($httpRequest);
    }

    public function sendViaGet()
    {
        $data = $this->getData();
        $apiUrl = implode('/', [
            $this->getApiUrl(),
            'echo',
            $data['merchantId'],
            $data['dttm'],
            urlencode($data['signature']),
        ]);

        $httpRequest = $this->httpClient->createRequest(
            RequestInterface::GET,
            $apiUrl
        );

        return $this->response = $this->sendAndProcessResponse($httpRequest);
    }

    /**
     * @param RequestInterface $httpRequest
     * @return EchoResponse
     */
    private function sendAndProcessResponse(RequestInterface $httpRequest)
    {
        $httpResponse = $httpRequest->send();
        $data = $httpResponse->json();

        $response = new EchoResponse($this, $data);
        $response->setVerifier($this->getVerifier());

        return $response;
    }
}
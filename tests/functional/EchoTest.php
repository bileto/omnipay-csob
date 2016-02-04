<?php

use Omnipay\Csob\Message\EchoResponse;

class EchoTest extends PHPUnit_Framework_TestCase
{
    /** @var \Omnipay\Csob\Gateway */
    private $gateway;

    private $merchantId = 'A1029DTmM7';

    public function setUp()
    {
        $publicKey = file_get_contents(__DIR__ . '/../unit/Sign/assets/mips_iplatebnibrana.csob.cz.pub');
        $privateKey = file_get_contents(__DIR__ . '/../unit/Sign/assets/rsa_A1029DTmM7.key');
        $this->gateway = \Omnipay\Csob\GatewayFactory::createInstance($publicKey, $privateKey);
    }

    /**
     * @see https://github.com/csob/paymentgateway/wiki/How-to-Switch-to-the-Live-Environment#communication-test---echo-get
     */
    public function testGet()
    {
        $response = $this->gateway->testGateway($this->merchantId, 'GET');

        $this->makeAsserts($response);
    }

    /**
     * @see https://github.com/csob/paymentgateway/wiki/How-to-Switch-to-the-Live-Environment#communication-test---echo-post
     */
    public function testPost()
    {
        $response = $this->gateway->testGateway($this->merchantId, 'POST');

        $this->makeAsserts($response);
    }

    private function makeAsserts(EchoResponse $response)
    {
        $data = $response->getData();
        $this->assertArrayHasKey('resultCode', $data, 'resultCode is in response data');
        $this->assertArrayHasKey('resultMessage', $data, 'resultMessage is in response data');
        $this->assertSame(0, $data['resultCode'], 'Result code is 0');
        $this->assertSame('OK', $data['resultMessage'], 'Result message is "OK"');
    }
}

<?php

class EchoTest extends PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $publicKey = file_get_contents(__DIR__ . '/../unit/Sign/assets/mips_iplatebnibrana.csob.cz.pub');
        $privateKey = file_get_contents(__DIR__ . '/../unit/Sign/assets/rsa_A1029DTmM7.key');
        $gateway = \Omnipay\Csob\GatewayFactory::createInstance($publicKey, $privateKey);
        $merchantId = 'A1029DTmM7';

        $response = $gateway->testGateway($merchantId, 'GET');
        $data = $response->getData();

        $this->assertArrayHasKey('resultCode', $data);
        $this->assertArrayHasKey('resultMessage', $data);
        $this->assertSame(0, $data['resultCode']);
        $this->assertSame('OK', $data['resultMessage']);
    }
}

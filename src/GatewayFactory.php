<?php

namespace Omnipay\Csob;

use Omnipay\Csob\Sign\DataSignator;
use Omnipay\Csob\Sign\DataVerifier;
use Omnipay\Csob\Sign\Preparer;
use Omnipay\Csob\Sign\Signator;
use Omnipay\Csob\Sign\Verifier;
use Omnipay\Omnipay;

class GatewayFactory
{
    /**
     * @param string $publicKey Content of the file
     * @param string $privateKey Content of the file
     * @param string $privateKeyPassword
     * @return Gateway
     */
    public static function createInstance($publicKey, $privateKey, $privateKeyPassword = null)
    {
        $preparer = new Preparer();
        $signator = new Signator($privateKey, $privateKeyPassword);
        $verifier = new Verifier($publicKey);
        $dataSignator = new DataSignator($preparer, $signator);
        $dataVerifier = new DataVerifier($preparer, $verifier);

        /** @var \Omnipay\Csob\Gateway $gateway */
        $gateway = Omnipay::create('Csob');
        $gateway->setSignator($dataSignator);
        $gateway->setVerifier($dataVerifier);
        return $gateway;
    }
}
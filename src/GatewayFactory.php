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
     * @param string $publicKeyFilename
     * @param string $privateKeyFilename
     * @param string $privateKeyPassword
     * @return Gateway
     */
    public static function createInstance($publicKeyFilename, $privateKeyFilename, $privateKeyPassword = null)
    {
        $preparer = new Preparer();
        $signator = new Signator($privateKeyFilename, $privateKeyPassword);
        $verifier = new Verifier($publicKeyFilename);
        $dataSignator = new DataSignator($preparer, $signator);
        $dataVerifier = new DataVerifier($preparer, $verifier);

        /** @var \Omnipay\Csob\Gateway $gateway */
        $gateway = Omnipay::create('Csob');
        $gateway->setSignator($dataSignator);
        $gateway->setVerifier($dataVerifier);
        return $gateway;
    }
}
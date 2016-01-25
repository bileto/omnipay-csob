<?php

class DataVerifierTest extends PHPUnit_Framework_TestCase
{
    public function testVerify()
    {
        $publicKey = file_get_contents(__DIR__ . '/assets/mips_iplatebnibrana.csob.cz.pub');
        $preparer = new \Omnipay\Csob\Sign\Preparer();
        $verifier = new \Omnipay\Csob\Sign\Verifier($publicKey);
        $dataVerifier = new \Omnipay\Csob\Sign\DataVerifier($preparer, $verifier);

        $data = [
            "authCode" => "518778",
            "dttm" => "20150624173114",
            "resultCode" => "0",
            "payId" => "18b7c0eced91417",
            "resultMessage" => "OK",
            "paymentStatus" => "7"
        ];
        $arrayKeys =['payId', 'dttm', 'resultCode', 'resultMessage', 'paymentStatus', 'authCode'];
        $signature = 'rnIFXMnHB3HW0xPgfhiScWZ6OMyp9iiaPzPf83aJ0MD5Fywf\/XPB6lVhOfqUfCC4qoD9YYZrWKPGyYAf7Fk6EK2qUewRdPSGLNcyX7xD5hWD65SJArXYhGwg9k3kkoxbMkAk\/tluTK6Hw2K65Xi2to1cIe\/lctXV2D92kisux6JKO9Ksw\/6eFOF3xFCWjIgxxy8\/oHQDo6EksNKef2SUH7fXPaG+A0SjuGeNs6kD3A8w2a40EZYZYcY00Ny9Xg2kV4uxRNHDFSph7LFkAo6G9p3j913\/A69ngX60hr13or+14cVgN2Lixk7RqjpdFHZ5bQjpShtTAT03WR+B2Z7pcg==';

        $isVerified = $dataVerifier->verify($data, $arrayKeys, $signature);

        $this->assertTrue($isVerified, 'Verification failed');
    }
}

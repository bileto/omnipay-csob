<?php

declare(strict_types=1);

use Omnipay\Csob\Sign\DataVerifier;
use Omnipay\Csob\Sign\Preparer;
use Omnipay\Csob\Sign\Verifier;
use PHPUnit\Framework\TestCase;

class DataVerifierTest extends TestCase
{
    public function testVerify(): void
    {
        $publicKey = file_get_contents('https://raw.githubusercontent.com/csob/platebnibrana/refs/heads/main/keys/mips_iplatebnibrana.csob.cz.pub');
        $preparer = new Preparer();
        $verifier = new Verifier($publicKey);
        $dataVerifier = new DataVerifier($preparer, $verifier);

        $data = [
            'authCode' => '446248',
            'dttm' => '20250114110115',
            'resultCode' => '0',
            'payId' => '9c4d4e0324c0@KA',
            'resultMessage' => 'OK',
            'paymentStatus' => '7',
        ];
        $arrayKeys =['payId', 'dttm', 'resultCode', 'resultMessage', 'paymentStatus', 'authCode'];
        $signature = 'GriDF8yDJzGaWWlLT422P7XuMHJKnv1a6htDtFxuiZEj6YLovks+JHqhAsD83jyYt8nRB8n\/Lr4iBgM1FiOtgYNp7EiVKthnqIQOlAzPHPPlvtYJUV\/LKVan010XIsADGnykYT11\/BswI+YBiShD7Rzz7SsthTBFrlqwGbmjN\/GJ2LgZUbIdJifHKZlaL+6ib\/x3fQfUl00T2RI7KUBBNqxE3nkdOHz+EzYQ7eb0Rp6ArKAgC+p9uQgn5uuU+xqtqknBi7pzEZ82gCrgtFM3HhwLi1X23RqFJM75AYSJKqX0jsP8\/JGrJLGzbmjFJ1K7GvlyxjQWh5EnsV4xc8kBlw==';

        $isVerified = $dataVerifier->verify($data, $arrayKeys, $signature);

        $this->assertTrue($isVerified, 'Verification failed');
    }
}

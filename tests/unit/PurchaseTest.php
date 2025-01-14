<?php

declare(strict_types=1);

use Mockery\LegacyMockInterface;
use Omnipay\Csob\CartItem;
use Omnipay\Csob\Purchase;
use PHPUnit\Framework\TestCase;

class PurchaseTest extends TestCase
{
    public function testToArray(): void
    {
        $purchase = new Purchase('merch123', '00456', 'http://eshop.com', 'Some desc');
        $item = new CartItem("Some item", 2, 150.50, "Some cart item");
        $purchase->addCartItem($item);
        $purchase->setDttm('xyz');
        $expected = [
            'merchantId' => 'merch123',
            'orderNo' => '00456',
            'dttm' => 'xyz',
            'payOperation' => 'payment',
            'payMethod' => 'card',
            'totalAmount' => 150.50,
            'currency' => 'CZK',
            'closePayment' => true,
            'returnUrl' => 'http://eshop.com',
            'returnMethod' => 'POST',
            'cart' => [
                [
                    'name' => 'Some item',
                    'quantity' => 2,
                    'amount' => 150.50,
                    'description' => 'Some cart item',
                ]
            ],
            'merchantData' => '',
            'customerId' => '',
            'language' => 'cs',
            'customer' => [],
            'order' => [],
            'ttlSec' => null,
            'customExpiry' => null,
            'logoVersion' => null,
            'colorSchemeVersion' => null,
        ];

        $export = $purchase->toArray();

        $this->assertEquals($expected, $export);
    }

    public function testDttmIsGenerated(): void
    {
        /** @var Purchase|LegacyMockInterface $purchaseMock */
        $purchaseMock = Mockery::mock(Purchase::class, ['merch123', '00456', 'http://eshop.com', 'Some description'])->makePartial();
        $purchaseMock->shouldAllowMockingProtectedMethods();
        $purchaseMock->shouldReceive('generateDttm')->andReturn('20150630094335');

        $exported = $purchaseMock->toArray();

        $this->assertSame('20150630094335', $exported['dttm']);
    }
}

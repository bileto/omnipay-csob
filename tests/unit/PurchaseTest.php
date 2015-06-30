<?php

use Omnipay\Csob\Purchase;

class PurchaseTest extends PHPUnit_Framework_TestCase
{
    public function testToArray()
    {
        $purchase = new Purchase('merch123', '00456', 'http://eshop.com', 'Some desc');
        $item = new \Omnipay\Csob\CartItem("Some item", 2, 150.50, "Some cart item");
        $purchase->addCartItem($item);
        $purchase->setDttm('xyz');
        $expected = [
            "merchantId" => 'merch123',
            "orderNo" => '00456',
            "dttm" => 'xyz',
            "payOperation" => 'payment',
            "payMethod" => 'card',
            "totalAmount" => 150.50,
            "currency" => 'CZK',
            "closePayment" => true,
            "returnUrl" => 'http://eshop.com',
            "returnMethod" => 'POST',
            "cart" => [
                [
                    'name' => 'Some item',
                    'quantity' => 2,
                    'amount' => 150.50,
                    'description' => 'Some cart item',
                ]
            ],
            "description" => 'Some desc',
            "merchantData" => '',
            "customerId" => '',
            "language" => 'CZ',
        ];

        $export = $purchase->toArray();

        $this->assertEquals($expected, $export);
    }

    public function testDttmIsGenerated()
    {
        $purchaseMock = $this->getMock(Purchase::class, ['generateDttm'], ['merch123', '00456', 'http://eshop.com', 'Some description']);
        $purchaseMock->expects($this->once())->method('generateDttm')->will($this->returnValue('20150630094335'));

        $export = $purchaseMock->toArray();

        $this->assertSame('20150630094335', $export['dttm']);
    }
}

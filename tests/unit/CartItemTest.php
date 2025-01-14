<?php

declare(strict_types=1);

use Omnipay\Csob\CartItem;
use PHPUnit\Framework\TestCase;

class CartItemTest extends TestCase
{
    public function testConstructor(): void
    {
        $item = new CartItem("Some item", 2, 150.50, "Some cart item");

        $this->assertSame("Some item", $item->getName());
        $this->assertSame(2, $item->getQuantity());
        $this->assertSame(150.50, $item->getAmount());
        $this->assertSame("Some cart item", $item->getDescription());
    }

    public function testToArray(): void
    {
        $item = new CartItem('Foo item', 4, 160.50, 'Foo description');
        $expected = [
            'name' => 'Foo item',
            'quantity' => 4,
            'amount' => 160.50,
            'description' => 'Foo description',
        ];

        $export = $item->toArray();

        $this->assertSame($expected, $export);
    }
}

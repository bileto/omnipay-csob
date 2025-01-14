# Omnipay: ČSOB

**ČSOB driver for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements ČSOB Online Payment Gateway support for Omnipay.

ČSOB Online Payment Gateway [documentation](https://github.com/csob/paymentgateway/wiki)

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "bileto/omnipay-csob": "~0.8"
    }
}
```
## TL;DR
```php
use Omnipay\Csob\GatewayFactory;

$publicKey = __DIR__ . '/tests/unit/Sign/assets/mips_iplatebnibrana.csob.cz.pub';
$privateKey = __DIR__ . '/tests/unit/Sign/assets/rsa_A1029DTmM7.key';
$gateway = GatewayFactory::createInstance($publicKey, $privateKey);

try {
    $merchantId = 'A1029DTmM7';
    $orderNo = '12345677';
    $returnUrl = 'http://localhost:8000/gateway-return.php';
    $description = 'Shopping at myStore.com (Lenovo ThinkPad Edge E540, Shipping with PPL)';

    $purchase = new \Omnipay\Csob\Purchase($merchantId, $orderNo, $returnUrl, $description);
    $purchase->setCart([
        new \Omnipay\Csob\CartItem("Notebook", 1, 1500000, "Lenovo ThinkPad Edge E540..."),
        new \Omnipay\Csob\CartItem("Shipping", 1, 0, "PPL"),
    ]);

    /** @var \Omnipay\Csob\Message\ProcessPaymentResponse $response */
    $response = $gateway->purchase($purchase->toArray())->send();

    // Payment init OK, redirect to the payment gateway
    echo $response->getRedirectUrl();
} catch (\Exception $e) {
    dump((string)$e);
}
```

## Test Project

The project uses PHPUnit and Mockery to provide unit tests.

```
./vendor/bin/phpunit
```

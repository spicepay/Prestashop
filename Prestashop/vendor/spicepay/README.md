# SpicePay PHP library

PHP library for SpicePay API.

You can sign up for a SpicePay account at <https://spicepay.com> for production and <https://sandbox.spicepay.com> for testing (sandbox).

## Composer

You can install library via [Composer](http://getcomposer.org/). Run the following command in your terminal:

```bash
composer require spicepay/spicepay-php
```

To use library, use Composer's autoload:

```php
require_once('vendor/autoload.php');
```

## Manual InstallationSpicePay

```php
require_once('/path/to/spicepay-php/init.php');
```

## Getting Started

Usage of SpicePay PHP library.

### Setting up SpicePay library

#### Setting default authentication

```php
\SpicePay\SpicePay::config(array('app_id' => 'YOUR_APP_ID', 'api_key' => 'YOUR_API_KEY', 'api_secret' => 'YOUR_API_SECRET'));

$order = \SpicePay\Merchant\Order::find(1087999);
```

#### Setting authentication individually

```php
# \SpicePay\Merchant\Order::find($orderId, $options = array(), $authentication = array())
$order = \SpicePay\Merchant\Order::find(1087999, array(), array('app_id' => 'YOUR_APP_ID', 'api_key' => 'YOUR_API_KEY', 'api_secret' => 'YOUR_API_SECRET'));
```

### Creating Merchant Order

https://developer.spicepay.com/docs/create-order

```php
$post_params = array(
                   'order_id'          => 'YOUR-CUSTOM-ORDER-ID-115',
                   'price'             => 1050.99,
                   'currency'          => 'USD',
                   'receive_currency'  => 'EUR',
                   'callback_url'      => 'https://example.com/payments/callback?token=6tCENGUYI62ojkuzDPX7Jg',
                   'cancel_url'        => 'https://example.com/cart',
                   'success_url'       => 'https://example.com/account/orders',
                   'title'             => 'Order #112',
                   'description'       => 'Apple Iphone 6'
               );

$order = \SpicePay\Merchant\Order::create($post_params);

if ($order) {
    echo $order->status;
} else {
    # Order Is Not Valid
}
```

### Getting Merchant Order

https://developer.spicepay.com/docs/get-order

```php
$order = \SpicePay\Merchant\Order::find(1087999);

if ($order) {
    echo $order->status;
} else {
    # Order Not Found
}
```


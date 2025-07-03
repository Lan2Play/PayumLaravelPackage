# Examples

## Paypal Express checkout

Described in [Get it started](get-it-started.md)

## Payment model

* Configuration

```bash
composer require payum/payum-laravel-package payum/offline
```

```php
// app/Providers/AppServiceProvider.php

public function register()
{
    $this->app->resolving('payum.builder', function(\Payum\Core\PayumBuilder $payumBuilder) {
        $payumBuilder
            ->addGateway('offline', ['factory' => 'offline']);
    });
}
```

* Prepare payment

```php
<?php
// app/Http/Controllers/PaymentController.php

use Payum\LaravelPackage\Controller\PayumController;

class PaymentController extends PayumController
{
 	public function preparePayment()
 	{
         $storage = $this->getPayum()->getStorage('Payum\Core\Model\Payment');

         $payment = $storage->create();
         $payment->setNumber(uniqid());
         $payment->setCurrencyCode('EUR');
         $payment->setTotalAmount(123); // 1.23 EUR
         $payment->setDescription('A description');
         $payment->setClientId('anId');
         $payment->setClientEmail('foo@example.com');
         $payment->setDetails([
           // put here any fields in a gateway format.
           // for example if you use Paypal ExpressCheckout you can define a description of the first item:
           // 'L_PAYMENTREQUEST_0_DESC0' => 'A desc',
         ]);
         $storage->update($payment);

         $captureToken = $this->getPayum()->getTokenFactory()->createCaptureToken('offline', $payment, 'payment_done');

         return redirect($captureToken->getTargetUrl());
 	}
}
```

## Stripe.Js

* Configuration

```bash
composer require payum/payum-laravel-package stripe/stripe-php payum/stripe
```

```php
// app/Providers/AppServiceProvider.php

public function register()
{
    $this->app->resolving('payum.builder', function(\Payum\Core\PayumBuilder $payumBuilder) {
        $payumBuilder
            ->addGateway('stripe_js', [
                'factory' => 'stripe_js',
                'publishable_key' => 'EDIT ME',
                'secret_key' => 'EDIT ME',
             ]);
    });
}
```

* Prepare payment

```php
<?php
// app/Http/Controllers/StripeController.php

use Payum\LaravelPackage\Controller\PayumController;

class StripeController extends PayumController
{
 	public function prepareJs()
 	{
         $storage = $this->getPayum()->getStorage('Payum\Core\Model\ArrayObject');
 
         $details = $storage->create();
         $details['amount'] = '100';
         $details['currency'] = 'USD';
         $details['description'] = 'a desc';
         $storage->update($details);
 
         $captureToken = $this->getPayum()->getTokenFactory()->createCaptureToken('stripe_js', $details, 'payment_done');
 
         return redirect($captureToken->getTargetUrl());
 	}
}
```

## Stripe Checkout

* Configuration

```bash
composer require payum/stripe payum/payum-laravel-package stripe/stripe-php
```

```php
// app/Providers/AppServiceProvider.php

public function register()
{
    $this->app->resolving('payum.builder', function(\Payum\Core\PayumBuilder $payumBuilder) {
        $payumBuilder
            ->addGateway('stripe_checkout', [
                'factory' => 'stripe_checkout',
                'publishable_key' => 'EDIT ME',
                'secret_key' => 'EDIT ME',
             ]);
    });
}
```

* Prepare payment

```php
<?php
// app/Http/Controllers/StripeController.php

use Payum\LaravelPackage\Controller\PayumController;

class StripeController extends PayumController
{
 	public function prepareCheckout()
 	{
         $storage = $this->getPayum()->getStorage('Payum\Core\Model\ArrayObject');
 
         $details = $storage->create();
         $details['amount'] = '100';
         $details['currency'] = 'USD';
         $details['description'] = 'a desc';
         $storage->update($details);
 
         $captureToken = $this->getPayum()->getTokenFactory()->createCaptureToken('stripe_checkout', $details, 'payment_done');
 
         return redirect($captureToken->getTargetUrl());
 	}
}
```

## Stripe Direct (via Omnipay)

* Configuration

```bash
composer require payum/omnipay-bridge payum/payum-laravel-package omnipay/stripe
```

```php
// app/Providers/AppServiceProvider.php

public function register()
{
    $this->app->resolving('payum.builder', function(\Payum\Core\PayumBuilder $payumBuilder) {
        $payumBuilder
            ->addGateway('stripe_direct', [
                'factory' => 'omnipay_direct',
                'type' => 'Stripe',
                'options' => [
                    'apiKey' => 'EDIT ME',
                    'testMode' => true,
                ],
             ]);
    });
}
```

* Prepare payment

```php
<?php
// app/Http/Controllers/OmnipayController.php

use Payum\LaravelPackage\Controller\PayumController;

class OmnipayController extends PayumController
{
 	public function prepareDirect()
 	{
         $storage = $this->getPayum()->getStorage('Payum\Core\Model\ArrayObject');
 
         $details = $storage->create();
         $details['amount'] = '10.00';
         $details['currency'] = 'USD';
         $storage->update($details);
 
         $captureToken = $this->getPayum()->getTokenFactory()->createCaptureToken('stripe_direct', $details, 'payment_done');
 
         return redirect($captureToken->getTargetUrl());
 	}
}
```

Back to [index](index.md).
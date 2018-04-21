# Coinbase Wallet Laravel Wrapper 




## Installation

Install the library using Composer. Please read the [Composer Documentation](https://getcomposer.org/doc/01-basic-usage.md) if you are unfamiliar with Composer or dependency managers in general.

```
composer require galal/coinbase
```

```php
Provider => Galal\Coinbase\CoinbaseServiceProvider::class,

```


```php
Aliase => 'Coinbase'=>Galal\Coinbase\Facades\Coinbase::class,

```


To get started, you'll need to publish all vendor assets:
```
$ php artisan vendor:publish
```

## Authentication

### API Key

Add an API key and secret of your own Coinbase account to config/coinbase.php .

```php

use Galal\Coinbase\Facades\Coinbase;

$client = Coinbase::client();
```

### OAuth2

Use OAuth2 authentication to access a user's account other than your own. This
library does not handle the handshake process, and assumes you have an access
token when it's initialized. You can handle the handshake process using an
[OAuth2 client] such as [league/oauth2-client].

```php
use Galal\Coinbase\Facades\Coinbase;

// with a refresh token
$client = Coinbase::clientUsingOauth($accessToken, $refreshToken);

// without a refresh token
$client = Coinbase::clientUsingOauth($accessToken);

```

### Two factor authentication

The send money endpoint requires a 2FA token in certain situations . A specific exception is thrown when this is required.

```php
use Galal\Coinbase\Facades\Coinbase;

$transaction = Coinbase::transaction()->send([
    'toEmail' => 'test@test.com',
    'bitcoinAmount' => 1
]);

$account = Coinbase::client()->getPrimaryAccount();
try {
    Coinbase::client()->createAccountTransaction($account, $transaction);
} catch (TwoFactorRequiredException $e) {
    // show 2FA dialog to user and collect 2FA token

    // retry call with token
    Coinbase::client()->createAccountTransaction($account, $transaction, [
        Coinbase::param()::TWO_FACTOR_TOKEN => '123456',
    ]);
}
```



### Warnings

It's prudent to be conscious of warnings. The library will log all warnings to a
standard PSR-3 logger if one is configured.

```php

use Galal\Coinbase\Facades\Coinbase;

$client = Coinbase::clientWithLogging($logger);
```


You can also request that the API return an expanded resource in the initial
request by using the `expand` parameter.

```php

$deposit = $this->client->getAccountDeposit($account, $depositId, [
    Coinbase::param()::EXPAND = ['transaction'],
]);
```

Resource references can be used when creating new resources, avoiding the
overhead of requesting a resource from the API.

```php

$deposit = Coinbase::deposit([
    'paymentMethod' => Coinbase::paymentMethod()->reference($paymentMethodId)
]);

// or use the convenience method
$deposit = Coinbase::deposit([
    'paymentMethodId' => $paymentMethodId
]);
```

### Responses

There are multiple ways to access raw response data. First, each resource
object has a `getRawData()` method which you can use to access any field that
are not mapped to the object properties.

```php
$data = $deposit->getRawData();
```

Raw data from the last HTTP response is also available on the client object.

```php
$data = $client->decodeLastResponse();
```

### Active record methods

The library includes support for active record methods on resource objects. You
must enable this functionality when bootstrapping your application.

```php
$client->enableActiveRecord();
```

Once enabled, you can call active record methods on resource objects.

```php

$transactions = $account->getTransactions([
    Coinbase::param()::FETCH_ALL => true,
]);
```

## Usage


**List supported native currencies**

```php
$currencies = $client->getCurrencies();
```

**List exchange rates**

```php
$rates = $client->getExchangeRates();
```

**Buy price**

```php
$buyPrice = $client->getBuyPrice('BTC-USD');
```

**Sell price**

```php
$sellPrice = $client->getSellPrice('BTC-USD');
```

**Spot price**

```php
$spotPrice = $client->getSpotPrice('BTC-USD');
```

**Current server time**

```php
$time = $client->getTime();
```


**Get authorization info**

```php
$auth = $client->getCurrentAuthorization();
```

**Lookup user info**

```php
$user = $client->getUser($userId);
```

**Get current user**

```php
$user = $client->getCurrentUser();
```

**Update current user**

```php
$user->setName('New Name');
$client->updateCurrentUser($user);
```


**List all accounts**

```php
$accounts = $client->getAccounts();
```

**List account details**

```php
$account = $client->getAccount($accountId);
```

**List primary account details**

```php
$account = $client->getPrimaryAccount();
```

**Set account as primary**

```php
$client->setPrimaryAccount($account);
```

**Create a new bitcoin account**

```php

$account = Coinbase::account([
    'name' => 'New Account'
]);
$client->createAccount($account);
```

**Update an account**

```php
$account->setName('New Account Name');
$client->updateAccount($account):
```

**Delete an account**

```php
$client->deleteAccount($account);
```


**List receive addresses for account**

```php
$addresses = $client->getAccountAddresses($account);
```

**Get receive address info**

```php
$address = $client->getAccountAddress($account, $addressId);
```

**List transactions for address**

```php
$transactions = $client->getAddressTransactions($address);
```

**Create a new receive address**

```php

$address = Coinbase::address([
    'name' => 'New Address'
]);
$client->createAccountAddress($account, $address);
```


**List transactions**

```php
$transactions = $client->getAccountTransactions($account);
```

**Get transaction info**

```php
$transaction = $client->getAccountTransaction($account, $transactionId);
```

**Send funds**

```php


$transaction = Coinbase::transaction()->send([
    'toBitcoinAddress' => 'ADDRESS',
    'amount'           => new Money(5, CurrencyCode::USD),
    'description'      => 'Your first bitcoin!',
    'fee'              => '0.0001' // only required for transactions under BTC0.0001
]);

$client->createAccountTransaction($account, $transaction);
```

**Transfer funds to a new account**

```php


$fromAccount = Coinbase::account()->reference($accountId);

$toAccount = Coinbase::account()([
    'name' => 'New Account'
]);
$client->createAccount($toAccount);

$transaction = Coinbase::transaction()->transfer([
    'to'            => $toAccount,
    'bitcoinAmount' => 1,
    'description'   => 'Your first bitcoin!'
]);

$client->createAccountTransaction($fromAccount, $transaction);
```

**Request funds**

```php


$transaction = Coinbase::transaction()->request([
    'amount'      => Coinbase::money(8, CurrencyCode::USD),
    'description' => 'Burrito'
]);

$client->createAccountTransaction($transaction);
```

**Resend request**

```php
$account->resendTransaction($transaction);
```

**Cancel request**

```php
$account->cancelTransaction($transaction);
```

**Fulfill request**

```php
$account->completeTransaction($transaction);
```


**List buys**

```php
$buys = $client->getAccountBuys($account);
```

**Get buy info**

```php
$buy = $client->getAccountBuy($account, $buyId);
```

**Buy bitcoins**

```php

$buy = Coinbase::buy([
    'bitcoinAmount' => 1
]);

$client->createAccountBuy($account, $buy);
```

**Commit a buy**

You only need to do this if you pass `commit=false` when you create the buy.

```php

$client->createAccountBuy($account, $buy, [Coinbase::param()::COMMIT => false]);
$client->commitBuy($buy);
```


**List sells**

```php
$sells = $client->getAccountSells($account);
```

**Get sell info**

```php
$sell = $client->getAccountSell($account, $sellId);
```

**Sell bitcoins**

```php

$sell = Coinbase::sell([
    'bitcoinAmount' => 1
]);

$client->createAccountSell($account, $sell);
```

**Commit a sell**

You only need to do this if you pass `commit=false` when you create the sell.

```php

$client->createAccountSell($account, $sell, [Coinbase::param()::COMMIT => false]);
$client->commitSell($sell);
```


**List deposits**

```php
$deposits = $client->getAccountDeposits($account);
```

**Get deposit info**

```php
$deposit = $client->getAccountDeposit($account, $depositId);
```

**Deposit funds**

```php


$deposit = Coinbase::deposit([
    'amount' => Coinbase::money(10, Coinbase::currencyCode()::USD)
]);

$client->createAccountDeposit($account, $deposit);
```

**Commit a deposit**

You only need to do this if you pass `commit=false` when you create the deposit.

```php
$client->createAccountDeposit($account, $deposit, [Coinbase::param()::COMMIT => false]);
$client->commitDeposit($deposit);
```


**List withdrawals**

```php
$withdrawals = $client->getAccountWithdrawals($account);
```

**Get withdrawal**

```php
$withdrawal = $client->getAccountWithdrawal($account, $withdrawalId);
```

**Withdraw funds**

```php

$withdrawal = Coinbase::withdrawal([
    'amount' => new Money(10, CurrencyCode::USD)
]);

$client->createAccountWithdrawal($account, $withdrawal);
```

**Commit a withdrawal**

You only need to do this if you pass `commit=true` when you call the withdrawal method.

```php

$client->createAccountWithdrawal($account, $withdrawal, [Coinbase::param()::COMMIT => false]);
$client->commitWithdrawal($withdrawal);
```


**List payment methods**

```php
$paymentMethods = $client->getPaymentMethods();
```

**Get payment method**

```php
$paymentMethod = $client->getPaymentMethod($paymentMethodId);
```


#### Get merchant

```php
$merchant = $client->getMerchant($merchantId);
```


#### List orders

```php
$orders = $client->getOrders();
```

#### Get order

```php
$order = $client->getOrder($orderId);
```

#### Create order

```php

$order = Coinbase::order([
    'name' => 'Order #1234',
    'amount' => Coinbase::moneyBTC(1)
]);

$client->createOrder($order);
```

#### Refund order

```php

$client->refundOrder($order, Coinbase::currencyCode()::BTC);
```

### Checkouts

#### List checkouts

```php
$checkouts = $client->getCheckouts();
```

#### Create checkout

```php

$params = array(
    'name'               => 'My Order',
    'amount'             => Coinbase::money(100, 'USD'),
    'metadata'           => array( 'order_id' => $custom_order_id )
);

$checkout = new Checkout($params);
$client->createCheckout($checkout);
$code = $checkout->getEmbedCode();
$redirect_url = "https://www.coinbase.com/checkouts/$code";
```

#### Get checkout

```php
$checkout = $client->getCheckout($checkoutId);
```

#### Get checkout's orders

```php
$orders = $client->getCheckoutOrders($checkout);
```

#### Create order for checkout

```php
$order = $client->createNewCheckoutOrder($checkout);
```

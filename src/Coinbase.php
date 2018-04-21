<?php
namespace Galal\Coinbase;
use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Enum\Param;
use Coinbase\Wallet\Resource\Address;
use Coinbase\Wallet\Resource\Transaction;
use Coinbase\Wallet\Resource\Deposit;
use Coinbase\Wallet\Resource\PaymentMethod;
use Coinbase\Wallet\Resource\Account;
use Coinbase\Wallet\Enum\CurrencyCode;
use Coinbase\Wallet\Value\Money;
use Coinbase\Wallet\Resource\Buy;
use Coinbase\Wallet\Resource\Sell;
use Coinbase\Wallet\Resource\Withdrawal;
use Coinbase\Wallet\Resource\Order;
use Coinbase\Wallet\Resource\Checkout;


class Coinbase {



    public function client(){
        $config= Configuration::apiKey(config('coinbase.auth.key'),config('coinbase.auth.secret'));
        return  Client::create($config);
    }

    public function clientWithLogging($logger){
        $config= Configuration::apiKey(config('coinbase.auth.key'),config('coinbase.auth.secret'));
        $config->setLogger($logger);
        return  Client::create($config);
    }
    public function clientUsingOauth($accessToken, $refreshToken=null){
        $config= Configuration::oauth($accessToken, $refreshToken=null);
        return  Client::create($config);
    }


    public function transaction(){
        return new Transaction;
    }
    public function deposit(array $params){
        return new Deposit($params);
    }
    public function paymentMethod(){
        return new PaymentMethod;
    }
    public function account(array $params){
        return new Account($params);
    }
    public function address(array $params){
        return new Address($params);
    }
    public function sell(array $params){
        return new Sell($params);
    }
    public function buy(array $params){
        return new Buy($params);
    }
    public function moneyBTC($amount){
        return  Money::btc($amount);
    }
    public function money($mount,$currency){
        return new Money($mount,$currency);
    }
    public function currencyCode(){
        return    CurrencyCode::class;
    }
    public function order(array $params){
        return new Order($params);
    }
    public function checkOut(array $params){
        return new Checkout($params);
    }
    public function withdrawal(array $params){
        return new Withdrawal($params);
    }
    public function param(){
        return  Param::class;
    }

}
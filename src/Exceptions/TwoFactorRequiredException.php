<?php

namespace Galal\Coinbase\Exeptions;


use Coinbase\Wallet\Exception\HttpException;

class TwoFactorRequiredException extends HttpException
{
}

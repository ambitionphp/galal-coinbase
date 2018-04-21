<?php
namespace Galal\Coinbase\Facades;

use Illuminate\Support\Facades\Facade;
class Coinbase extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'Coinbase';
    }

}
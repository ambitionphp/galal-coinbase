<?php
return [
    /*
    |--------------------------------------------------------------------------
    | coinbase authentication
    |--------------------------------------------------------------------------
    |
    | Authentication key and secret for coinbase API.
    |
     */

    'auth' => [
        'key'    => env('COINBASE_KEY', ''),
        'secret' => env('COINBASE_SECRET', ''),
    ],



];

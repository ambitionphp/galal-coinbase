<?php

namespace Galal\Coinbase;

use Illuminate\Support\ServiceProvider;

class CoinbaseServiceProvider extends ServiceProvider
{

    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!file_exists(config_path('coinbase.php'))) {
            $this->publishes([
                realpath(__DIR__ . '/../config/coinbase.php') => config_path('coinbase.php'),
            ]);
        }

    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('Coinbase',function (){
            return new Coinbase;
        });
    }

    public function provides()
    {
        return ['Coinbase'];
    }
}

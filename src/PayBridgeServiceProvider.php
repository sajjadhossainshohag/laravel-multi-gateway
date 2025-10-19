<?php

namespace PaymentSetu\PayBridge;

use Illuminate\Support\ServiceProvider;

class PayBridgeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/paybridge.php', 'paybridge');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/paybridge.php' => config_path('paybridge.php'),
        ]);
    }
}

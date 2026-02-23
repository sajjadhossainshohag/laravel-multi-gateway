<?php

namespace PaymentSetu\PayBridge;

use Illuminate\Support\ServiceProvider;

class PayBridgeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/paybridge.php', 'paybridge');

        $this->app->singleton('paybridge', function ($app) {
            return new GatewayManager($app);
        });
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/paybridge.php' => config_path('paybridge.php'),
            ], 'paybridge-config');
        }
    }
}

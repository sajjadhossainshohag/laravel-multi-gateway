<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use PaymentSetu\PayBridge\PayBridgeServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \PaymentSetu\PayBridge\PayBridgeServiceProvider::class,
            \Workbench\App\Providers\WorkbenchServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'PayBridge' => \PaymentSetu\PayBridge\Facades\PayBridge::class,
        ];
    }
}

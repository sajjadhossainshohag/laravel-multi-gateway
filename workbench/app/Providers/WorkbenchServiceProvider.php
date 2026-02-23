<?php

namespace Workbench\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class WorkbenchServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Route::middleware('web')
            ->group(__DIR__ . '/../../routes/web.php');

        // Setup IPN route for testing
        Route::any('/paybridge/ipn/{gateway}', \PaymentSetu\PayBridge\Http\Controllers\IpnController::class);
    }
}

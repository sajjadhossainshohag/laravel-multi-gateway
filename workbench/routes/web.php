<?php

use Illuminate\Support\Facades\Route;
use PaymentSetu\PayBridge\Facades\PayBridge;

Route::get('/', function () {
    return '<h1>Payment Gateway Workbench</h1>
            <p>Use the following routes to test:</p>
            <ul>
                <li><a href="/test-stripe">/test-stripe</a> - Test Stripe Charge</li>
                <li><a href="/test-ipn-url">/test-ipn-url</a> - See your IPN URL</li>
            </ul>';
});

Route::get('/test-stripe', function () {
    // Note: You must set these in your .env file
    config(['paybridge.gateways.stripe' => [
        'driver' => 'stripe',
        'api_secret' => env('STRIPE_SECRET', 'sk_test_51KHQhKAmfDlh6wQqXfg4ZScnTRahxbdXV0mKw30nOI4f8gtB2v5rho7IyJtZqkf8SwwuNgLTO2WPGFyk9vnFl8gO00MhSe8Kbj'),
    ]]);

    try {
        $response = PayBridge::gateway('stripe')->charge([
            'amount' => 10,
            'currency' => 'usd',
            'success_url' => url('/test-stripe?status=success'),
            'cancel_url' => url('/test-stripe?status=cancel'),
            'description' => 'Workbench Manual Test'
        ]);

        if ($response->isRedirect()) {
            return redirect($response->getRedirectUrl());
        }

        return [
            'status' => 'failed',
            'message' => $response->getErrorMessage()
        ];
    } catch (\Exception $e) {
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
});

Route::get('/test-ipn-url', function () {
    return [
        'info' => 'Your generic IPN endpoint is configured as:',
        'url' => url('/paybridge/ipn/stripe'),
        'instruction' => 'In a real app, you would point Stripe webhooks here.'
    ];
});

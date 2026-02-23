<?php

use Illuminate\Support\Facades\Http;
use PaymentSetu\PayBridge\Facades\PayBridge;
use PaymentSetu\PayBridge\Gateways\Stripe\StripeGateway;

it('sends correct data to stripe when charging via checkout', function () {
    // 1. Mock the Stripe API
    Http::fake([
        'api.stripe.com/v1/checkout/sessions' => Http::response([
            'id' => 'cs_test_123',
            'url' => 'https://checkout.stripe.com/pay/cs_test_123',
        ], 200)
    ]);

    // 2. Configure stripe
    config(['paybridge.gateways.stripe' => [
        'driver' => 'stripe',
        'api_secret' => 'sk_test_secret_key',
    ]]);

    // 3. Charge
    $response = PayBridge::gateway('stripe')->charge([
        'amount' => 50,
        'currency' => 'usd',
        'success_url' => 'http://example.com/success',
        'cancel_url' => 'http://example.com/cancel',
        'description' => 'Test order'
    ]);

    // 4. VERIFY: Assert the request structure
    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.stripe.com/v1/checkout/sessions' &&
            $request['success_url'] === 'http://example.com/success' &&
            $request['cancel_url'] === 'http://example.com/cancel' &&
            isset($request['line_items'][0]['price_data']['unit_amount']) &&
            $request['line_items'][0]['price_data']['unit_amount'] == 5000;
    });

    expect($response->isSuccessful())->toBeTrue();
    expect($response->isRedirect())->toBeTrue();
    expect($response->getRedirectUrl())->toBe('https://checkout.stripe.com/pay/cs_test_123');
    expect($response->getTransactionReference())->toBe('cs_test_123');
});

it('handles error responses from stripe correctly', function () {
    Http::fake([
        'api.stripe.com/v1/checkout/sessions' => Http::response([
            'error' => [
                'message' => 'API Error.',
                'code' => 'parameter_missing'
            ]
        ], 402)
    ]);

    config(['paybridge.gateways.stripe.api_secret' => 'key']);

    $response = PayBridge::gateway('stripe')->charge(['amount' => 10]);

    expect($response->isSuccessful())->toBeFalse();
    expect($response->getErrorMessage())->toBe('API Error.');
});

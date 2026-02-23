<?php

use Illuminate\Support\Facades\Http;
use PaymentSetu\PayBridge\Facades\PayBridge;
use PaymentSetu\PayBridge\Gateways\Stripe\StripeGateway;

it('sends correct data to stripe when charging', function () {
    // 1. Mock the Stripe API
    Http::fake([
        'api.stripe.com/v1/charges' => Http::response([
            'id' => 'ch_test_123',
            'status' => 'succeeded',
        ], 200)
    ]);

    // 2. Configure stripe
    config(['paybridge.gateways.stripe' => [
        'driver' => 'stripe',
        'api_secret' => 'sk_test_secret_key',
    ]]);

    // 3. Discharge
    $response = PayBridge::gateway('stripe')->charge([
        'amount' => 50, // dollars
        'currency' => 'usd',
        'token' => 'tok_visa',
        'description' => 'Test order'
    ]);

    // 4. VERIFY IT REALLY WORKS: Assert the request structure
    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.stripe.com/v1/charges' &&
            $request['amount'] == 5000 && // Verify conversion to cents
            $request['currency'] === 'usd' &&
            $request['source'] === 'tok_visa';
    });

    expect($response->isSuccessful())->toBeTrue();
    expect($response->getTransactionReference())->toBe('ch_test_123');
});

it('handles error responses from stripe correctly', function () {
    Http::fake([
        'api.stripe.com/v1/charges' => Http::response([
            'error' => [
                'message' => 'Your card was declined.',
                'code' => 'card_declined'
            ]
        ], 402)
    ]);

    config(['paybridge.gateways.stripe.api_secret' => 'key']);

    $response = PayBridge::gateway('stripe')->charge(['amount' => 10, 'token' => 'bad']);

    expect($response->isSuccessful())->toBeFalse();
    expect($response->getErrorMessage())->toBe('Your card was declined.');
});

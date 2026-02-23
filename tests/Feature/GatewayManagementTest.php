<?php

use PaymentSetu\PayBridge\Facades\PayBridge;
use Tests\Fakes\FakeGateway;

it('can register and use a custom fake gateway', function () {
    // 1. You can extend the manager with a custom driver for testing
    PayBridge::extend('test_gateway', function () {
        return new FakeGateway();
    });

    // 2. Access your fake gateway
    $gateway = PayBridge::gateway('test_gateway');

    // 3. Test success scenario
    $response = $gateway->charge(['amount' => 100]);
    expect($response->isSuccessful())->toBeTrue();
    expect($response->getTransactionReference())->toBe('fake_123');
});

it('can test failures using the fake gateway', function () {
    PayBridge::extend('error_gateway', function () {
        $fake = new FakeGateway();
        $fake->shouldFail = true;
        return $fake;
    });

    $response = PayBridge::gateway('error_gateway')->charge(['amount' => 100]);

    expect($response->isSuccessful())->toBeFalse();
    expect($response->getErrorMessage())->toBe('Fake failure message');
});

it('resolves the default gateway from config', function () {
    // Mock the config
    config(['paybridge.default' => 'test_mock']);
    config(['paybridge.gateways.test_mock' => ['driver' => 'test_mock']]);

    PayBridge::extend('test_mock', function () {
        return new FakeGateway();
    });

    $gateway = PayBridge::gateway(); // No name passed, should use default

    expect($gateway)->toBeInstanceOf(FakeGateway::class);
});

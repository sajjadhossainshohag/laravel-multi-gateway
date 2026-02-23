<?php

namespace Tests\Fakes;

use PaymentSetu\PayBridge\Contracts\GatewayInterface;
use PaymentSetu\PayBridge\Contracts\GatewayResponseInterface;

class FakeGateway implements GatewayInterface
{
    public $shouldFail = false;

    public function charge(array $parameters): GatewayResponseInterface
    {
        return new FakeResponse($this->shouldFail, ['ref' => 'fake_123']);
    }

    public function refund(array $parameters): GatewayResponseInterface
    {
        return new FakeResponse($this->shouldFail, ['ref' => 'fake_ref_123']);
    }

    public function withdraw(array $parameters): GatewayResponseInterface
    {
        return new FakeResponse($this->shouldFail, ['ref' => 'fake_with_123']);
    }

    public function handleIpn(mixed $request): GatewayResponseInterface
    {
        return new FakeResponse($this->shouldFail, ['ref' => 'fake_ipn_123']);
    }

    public function supports(string $method): bool
    {
        return true;
    }

    public function isSandbox(): bool
    {
        return true;
    }
}

<?php

namespace Tests\Fakes;

use PaymentSetu\PayBridge\Contracts\GatewayResponseInterface;

class FakeResponse implements GatewayResponseInterface
{
    protected $failed;
    protected $data;

    public function __construct(bool $failed = false, array $data = [])
    {
        $this->failed = $failed;
        $this->data = $data;
    }

    public function isSuccessful(): bool
    {
        return !$this->failed;
    }

    public function isRedirect(): bool
    {
        return false;
    }

    public function getRedirectUrl(): ?string
    {
        return null;
    }

    public function getTransactionReference(): ?string
    {
        return $this->data['ref'] ?? null;
    }

    public function getMessage(): ?string
    {
        return $this->failed ? 'Fake failure message' : 'Success';
    }

    public function getData(): array
    {
        return $this->data;
    }
}

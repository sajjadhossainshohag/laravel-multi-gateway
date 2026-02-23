<?php

namespace PaymentSetu\PayBridge\Gateways\Stripe;

use PaymentSetu\PayBridge\Contracts\GatewayResponseInterface;

class StripeResponse implements GatewayResponseInterface
{
    protected $data;
    protected $status;

    public function __construct(array $data, int $status)
    {
        $this->data = $data;
        $this->status = $status;
    }

    public function isSuccessful(): bool
    {
        return $this->status >= 200 && $this->status < 300 && !isset($this->data['error']);
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
        return $this->data['id'] ?? null;
    }

    public function getErrorMessage(): ?string
    {
        return $this->data['error']['message'] ?? ($this->isSuccessful() ? 'Success' : 'API Error');
    }

    public function getData(): array
    {
        return $this->data;
    }
}

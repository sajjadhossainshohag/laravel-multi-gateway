<?php


namespace PaymentSetu\PayBridge\Contracts;

interface GatewayResponseInterface
{
    public function isSuccessful(): bool;

    public function isRedirect(): bool;

    public function getRedirectUrl(): ?string;

    public function getTransactionReference(): ?string;

    public function getMessage(): ?string;

    public function getData(): array;
}

<?php


namespace PaymentSetu\PayBridge\Contracts;

interface GatewayInterface
{
    public function charge(array $parameters): GatewayResponseInterface;

    public function refund(array $parameters): GatewayResponseInterface;

    public function withdraw(array $parameters): GatewayResponseInterface;

    public function handleIpn(mixed $request): GatewayResponseInterface;

    public function supports(string $method): bool;

    public function isSandbox(): bool;
}

<?php


namespace PaymentSetu\PayBridge\Contracts;

interface GatewayInterface
{
    public function purchase(array $parameters): GatewayResponseInterface;

    public function authorize(array $parameters): GatewayResponseInterface;

    public function capture(array $parameters): GatewayResponseInterface;

    public function refund(array $parameters): GatewayResponseInterface;

    public function void(array $parameters): GatewayResponseInterface;

    public function withdraw(array $parameters): GatewayResponseInterface;

    public function handleIpn(mixed $request): GatewayResponseInterface;

    public function supports(string $method): bool;
}

<?php

namespace PaymentSetu\PayBridge\Gateways\Stripe;

use Illuminate\Support\Facades\Http;
use PaymentSetu\PayBridge\Contracts\GatewayInterface;
use PaymentSetu\PayBridge\Contracts\GatewayResponseInterface;

class StripeGateway implements GatewayInterface
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function charge(array $parameters): GatewayResponseInterface
    {
        $response = Http::withToken($this->config['api_secret'])
            ->asForm()
            ->post($this->getBaseUrl() . '/charges', [
                'amount' => $parameters['amount'] * 100, // Convert to cents
                'currency' => $parameters['currency'] ?? 'usd',
                'source' => $parameters['token'],
                'description' => $parameters['description'] ?? '',
            ]);

        return new StripeResponse($response->json(), $response->status());
    }

    public function refund(array $parameters): GatewayResponseInterface
    {
        $response = Http::withToken($this->config['api_secret'])
            ->asForm()
            ->post($this->getBaseUrl() . '/refunds', [
                'charge' => $parameters['transaction_id'],
                'amount' => isset($parameters['amount']) ? $parameters['amount'] * 100 : null,
            ]);

        return new StripeResponse($response->json(), $response->status());
    }

    public function withdraw(array $parameters): GatewayResponseInterface
    {
        // Stripe Payouts example
        $response = Http::withToken($this->config['api_secret'])
            ->asForm()
            ->post($this->getBaseUrl() . '/payouts', [
                'amount' => $parameters['amount'] * 100,
                'currency' => $parameters['currency'] ?? 'usd',
            ]);

        return new StripeResponse($response->json(), $response->status());
    }

    public function handleIpn(mixed $request): GatewayResponseInterface
    {
        // Simple example: Stripe sends JSON in request body
        $data = is_array($request) ? $request : $request->all();
        return new StripeResponse($data, 200);
    }

    protected function getBaseUrl(): string
    {
        return $this->isSandbox()
            ? 'https://api.stripe.com/v1' // Test mode URL
            : 'https://api.stripe.com/v1'; // Live mode URL (Stripe uses the same, but others don't)
    }

    public function supports(string $method): bool
    {
        return in_array($method, ['charge', 'refund', 'withdraw', 'ipn']);
    }

    public function isSandbox(): bool
    {
        return (bool) ($this->config['sandbox'] ?? config('paybridge.sandbox', true));
    }
}

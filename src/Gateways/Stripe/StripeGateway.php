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
            ->post('https://api.stripe.com/v1/charges', [
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
            ->post('https://api.stripe.com/v1/refunds', [
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
            ->post('https://api.stripe.com/v1/payouts', [
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

    public function supports(string $method): bool
    {
        return in_array($method, ['charge', 'refund', 'withdraw', 'ipn']);
    }
}

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

    /**
     * @param array{
     *  amount: float|int,
     *  currency: string,
     *  success_url: string,
     *  cancel_url: string,
     *  description?: string,
     *  line_items?: array,
     *  mode?: "payment"|"subscription"|"setup",
     *  metadata?: array
     * } $parameters
     */
    public function charge(array $parameters): GatewayResponseInterface
    {
        $payload = [
            'success_url' => $parameters['success_url'] ?? url('/'),
            'cancel_url' => $parameters['cancel_url'] ?? url('/'),
            'mode' => $parameters['mode'] ?? 'payment',
        ];

        if (isset($parameters['line_items'])) {
            $payload['line_items'] = $parameters['line_items'];
        } else {
            $payload['line_items'] = [
                [
                    'price_data' => [
                        'currency' => $parameters['currency'] ?? 'usd',
                        'unit_amount' => $parameters['amount'] * 100,
                        'product_data' => [
                            'name' => $parameters['description'] ?? 'Payment',
                        ],
                    ],
                    'quantity' => 1,
                ],
            ];
        }

        $response = Http::withToken($this->config['api_secret'])
            ->asForm()
            ->post($this->getBaseUrl() . '/checkout/sessions', $payload);

        return new StripeResponse($response->json(), $response->status());
    }

    /**
     * @param array{
     *  transaction_id: string,
     *  amount?: float|int,
     *  reason?: string
     * } $parameters
     */
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

    /**
     * @param array{
     *  amount: float|int,
     *  currency: string,
     *  destination?: string,
     *  metadata?: array
     * } $parameters
     */
    public function withdraw(array $parameters): GatewayResponseInterface
    {
        // Stripe Payouts
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
        $data = is_array($request) ? $request : $request->all();
        return new StripeResponse($data, 200);
    }

    protected function getBaseUrl(): string
    {
        return 'https://api.stripe.com/v1'; // Live mode URL (Stripe uses the same for both)
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

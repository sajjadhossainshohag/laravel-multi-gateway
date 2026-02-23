<?php

namespace PaymentSetu\PayBridge\Contracts;

interface IpnHandler
{
    /**
     * Handle the verified IPN response.
     *
     * @param  \PaymentSetu\PayBridge\Contracts\GatewayResponseInterface  $response
     * @param  string  $gateway
     * @return void
     */
    public function handle(GatewayResponseInterface $response, string $gateway);
}

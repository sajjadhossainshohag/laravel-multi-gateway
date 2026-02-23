<?php

namespace PaymentSetu\PayBridge\Events;

use PaymentSetu\PayBridge\Contracts\GatewayResponseInterface;

class PaymentFailed
{
    public $response;
    public $gateway;

    public function __construct(GatewayResponseInterface $response, string $gateway)
    {
        $this->response = $response;
        $this->gateway = $gateway;
    }
}

<?php

namespace PaymentSetu\PayBridge\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \PaymentSetu\PayBridge\Contracts\GatewayInterface gateway(string|null $name = null)
 * @method static \PaymentSetu\PayBridge\Contracts\GatewayInterface driver(string|null $driver = null)
 * @method static \PaymentSetu\PayBridge\Contracts\GatewayResponseInterface charge(array $parameters)
 * @method static \PaymentSetu\PayBridge\Contracts\GatewayResponseInterface refund(array $parameters)
 * @method static \PaymentSetu\PayBridge\Contracts\GatewayResponseInterface withdraw(array $parameters)
 * @method static \PaymentSetu\PayBridge\Contracts\GatewayResponseInterface handleIpn(mixed $request)
 *
 * @see \PaymentSetu\PayBridge\GatewayManager
 */
class PayBridge extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'paybridge';
    }
}

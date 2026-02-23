<?php

namespace PaymentSetu\PayBridge\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \PaymentSetu\PayBridge\Contracts\GatewayInterface gateway(string|null $name = null)
 * @method static \PaymentSetu\PayBridge\Contracts\GatewayInterface driver(string|null $driver = null)
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

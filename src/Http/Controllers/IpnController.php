<?php

namespace PaymentSetu\PayBridge\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PaymentSetu\PayBridge\Facades\PayBridge;

class IpnController extends Controller
{
    /**
     * Handle the incoming IPN request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $gateway
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, string $gateway)
    {
        $instance = PayBridge::gateway($gateway);

        $response = $instance->handleIpn($request);

        if ($handlerClass = config('paybridge.ipn_handler')) {
            $handler = app($handlerClass);
            $handler->handle($response, $gateway);
        }

        return response()->noContent();
    }
}

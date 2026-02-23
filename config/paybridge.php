<?php

return [
    /*
    * Set default Gateway
    */
    "default" => env('PAYBRIDGE_GATEWAY', 'stripe'),

    /*
    * Set true for Sandbox/Test Mode.
    * If set to false, it will operate in Live/Production mode.
    */
    "sandbox" => env('PAYBRIDGE_SANDBOX', true),

    /*
    * Gateways configuration
    */
    "gateways" => [
        "stripe" => [
            "driver" => "stripe",
            "api_key" => env('STRIPE_KEY'),
            "api_secret" => env('STRIPE_SECRET'),
            "sandbox" => env('STRIPE_SANDBOX', env('PAYBRIDGE_SANDBOX', true)),
        ],
    ],

    /*
    * Custom IPN Handler
    * This class should implement PaymentSetu\PayBridge\Contracts\IpnHandler
    */
    "ipn_handler" => null,
];

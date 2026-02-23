<?php

return [
    /* 
    * Set default Gateway
    */
    "default" => env('PAYBRIDGE_GATEWAY', 'stripe'),

    /*
    * Gateways configuration
    */
    "gateways" => [
        "stripe" => [
            "driver" => "stripe",
            "api_key" => env('STRIPE_KEY'),
            "api_secret" => env('STRIPE_SECRET'),
        ],
    ],

    /*
    * Custom IPN Handler
    * This class should implement PaymentSetu\PayBridge\Contracts\IpnHandler
    */
    "ipn_handler" => null,
];

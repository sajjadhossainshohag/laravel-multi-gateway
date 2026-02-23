<?php

namespace PaymentSetu\PayBridge\Events;

class PaymentStarted
{
    /**
     * @var array
     */
    public $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
}

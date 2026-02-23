<?php

namespace PaymentSetu\PayBridge;

use Illuminate\Support\Manager;
use InvalidArgumentException;
use PaymentSetu\PayBridge\Contracts\GatewayInterface;

class GatewayManager extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->config->get('paybridge.default');
    }

    /**
     * Create a new gateway instance.
     *
     * @param  string  $driver
     * @return GatewayInterface
     *
     * @throws \InvalidArgumentException
     */
    protected function createDriver($driver)
    {
        // First, check if a custom driver creator exists.
        if (isset($this->customCreators[$driver])) {
            return $this->callCustomCreator($driver);
        }

        $config = $this->config->get("paybridge.gateways.{$driver}");

        if (is_null($config)) {
            throw new InvalidArgumentException("Gateway [{$driver}] is not defined.");
        }

        $method = 'create' . ucfirst($config['driver'] ?? $driver) . 'Driver';

        if (method_exists($this, $method)) {
            return $this->$method($config);
        }

        throw new InvalidArgumentException("Driver [{$driver}] not supported.");
    }

    /**
     * Get a gateway instance.
     *
     * @param  string|null  $name
     * @return GatewayInterface
     */
    public function gateway($name = null)
    {
        return $this->driver($name);
    }
}

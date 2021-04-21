<?php

namespace TNLMedia\MemberSDK;

use TNLMedia\MemberSDK\Clients\Client;

/**
 * Class MemberSDK
 * @package TNLMedia\MemberSDK
 */
class MemberSDK
{
    /**
     * Clients
     *
     * @var array
     */
    protected $clients = [];

    public function __construct(array $config = [])
    {
        // TODO
    }

    /**
     * Property magic
     *
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        // Get client
        $this->initClient($name);
        if ($this->clients[$name]) {
            return $this->clients[$name];
        }

        // Error
        $trace = debug_backtrace();
        trigger_error(
            'Undefined property: ' . get_class($this) . '::$' . $name .
            ' in ' . $trace[0]['file'] . ' on line ' . $trace[0]['line'],
            E_USER_NOTICE
        );
        return null;
    }

    /**
     * Initial client into property
     *
     * @param string $name
     */
    protected function initClient(string $name)
    {
        // Check exists
        $key = strtolower($name);
        if ($this->services[$key]) {
            return;
        }

        // Check class
        $namespace = __NAMESPACE__ . '\\' . ucfirst($key) . 'Client';
        if (!is_subclass_of($namespace, Client::class)) {
            return;
        }

        // Initial
        $this->clients[$key] = new $namespace($this);
    }
}

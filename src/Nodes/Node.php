<?php

namespace TNLMedia\MemberSDK\Nodes;

use TNLMedia\MemberSDK\MemberSDK;

class Node implements NodeInterface
{
    /**
     * Token attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Core class
     *
     * @var MemberSDK
     */
    protected $core;

    /**
     * {@inheritDoc}
     */
    public function __construct(array $attributes = [], MemberSDK $core = null)
    {
        $this->initial($attributes);
        $this->setSdkCore($core);
    }

    /**
     * {@inheritDoc}
     */
    public function initial(array $attributes = [])
    {
        return $this->setAttributes(null, $attributes);
    }

    /**
     * Set request sdk
     *
     * @param MemberSDK|null $core
     */
    public function setSdkCore(MemberSDK $core = null)
    {
        $this->core = $core;
    }

    /**
     * {@inheritDoc}
     */
    public function setAttributes($key = null, $value = null)
    {
        if (is_null($key)) {
            $this->attributes = $value;
            return $this;
        }
        $key = strval($key);

        // Depth down
        $array = &$this->attributes;
        $keys = explode('.', $key);
        foreach ($keys as $level => $key) {
            if (count($keys) === 1) {
                break;
            }

            unset($keys[$level]);
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        // Set
        $array[array_shift($keys)] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function resetAttributes()
    {
        $this->setAttributes();
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes($key = null, $default = null)
    {
        // Without key
        if (is_null($key)) {
            return $this->attributes;
        }
        $key = strval($key);

        // Direct key exists
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        // Depth down
        $array = $this->attributes;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array)) {
                return $default;
            }
            if (!array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Get attributes as string
     *
     * @param string|null $key
     * @param string $default
     * @return string
     */
    public function getStringAttributes($key = null, string $default = '')
    {
        return strval($this->getAttributes($key, $default));
    }

    /**
     * Get attributes as integer
     *
     * @param string|null $key
     * @param int $default
     * @return int
     */
    public function getIntegerAttributes($key = null, int $default = 0)
    {
        return intval($this->getAttributes($key, $default));
    }

    /**
     * Get attributes as boolean
     *
     * @param null $key
     * @param bool $default
     * @return bool
     */
    public function getBooleanAttributes($key = null, bool $default = false)
    {
        return boolval($this->getAttributes($key, $default));
    }

    /**
     * Get attributes as array
     *
     * @param string|null $key
     * @param array $default
     * @return array
     */
    public function getArrayAttributes($key = null, array $default = [])
    {
        return (array)$this->getAttributes($key, $default);
    }
}

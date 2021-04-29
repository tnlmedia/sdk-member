<?php

namespace TNLMedia\MemberSDK\Nodes;

interface NodeInterface
{
    /**
     * NodeInterface constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = []);

    /**
     * Initial node attributes
     *
     * @param array $attributes
     * @return $this
     */
    public function initial(array $attributes = []);

    /**
     * Set attributes use dot notation
     *
     * @param string|null $key
     * @param mixed $value
     * @return $this
     */
    public function setAttributes($key = null, $value = null);

    /**
     * Clear attributes
     *
     * @return $this
     */
    public function resetAttributes();

    /**
     * Get attributes use dot notation
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function getAttributes($key = null, $default = null);

    /**
     * Set relations
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setRelations(string $key, $value = null);

    /**
     * Clear relations
     *
     * @return $this
     */
    public function resetRelations();

    /**
     * Get relations
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function getRelations($key = null, $default = null);
}

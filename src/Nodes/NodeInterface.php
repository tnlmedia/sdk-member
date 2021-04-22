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
}

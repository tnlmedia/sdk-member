<?php

namespace TNLMedia\MemberSDK\Clients;

use TNLMedia\MemberSDK\MemberSDK;

class Client
{
    /**
     * Core class
     *
     * @var MemberSDK
     */
    protected $core;

    /**
     * Service constructor.
     *
     * @param MemberSDK $core
     */
    public function __construct(MemberSDK $core)
    {
        $this->core = $core;
    }
}

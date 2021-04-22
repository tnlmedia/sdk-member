<?php

namespace TNLMedia\MemberSDK\Helpers;

use TNLMedia\MemberSDK\MemberSDK;

class Helper
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

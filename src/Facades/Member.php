<?php

namespace Tnlmedia\MemberSDK\Facades;

use Illuminate\Support\Facades\Facade;

class MemberSDK extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'member-sdk';
    }
}

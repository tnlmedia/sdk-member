<?php

namespace Tnlmedia\MemberSDK;

use Illuminate\Support\Facades\Facade;

class MemberSDKFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'member-sdk';
    }
}

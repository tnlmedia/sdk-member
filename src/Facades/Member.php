<?php

namespace Tnlmedia\Member\Facades;

use Illuminate\Support\Facades\Facade;

class Member extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'member';
    }
}

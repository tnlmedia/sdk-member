<?php

namespace TNLMedia\MemberSDK\Exceptions;

/**
 * Class ProtectedException
 * @package TNLMedia\MemberSDK\Exceptions
 * @method static ProtectedException invalidField(string $hint = '')
 */
class ProtectedException extends Exception
{
    /**
     * {@inheritDoc}
     */
    protected $code = 42301;
}

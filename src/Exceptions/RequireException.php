<?php

namespace TNLMedia\MemberSDK\Exceptions;

/**
 * Class RequireException
 * @package TNLMedia\MemberSDK\Exceptions
 * @method static RequireException invalidField(string $hint = '')
 */
class RequireException extends Exception
{
    /**
     * {@inheritDoc}
     */
    protected $code = 40001;
}

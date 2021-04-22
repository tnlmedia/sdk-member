<?php

namespace TNLMedia\MemberSDK\Exceptions;

/**
 * Class AuthorizeException
 * @package TNLMedia\MemberSDK\Exceptions
 * @method static AuthorizeException invalidField(string $hint = '')
 */
class AuthorizeException extends Exception
{
    /**
     * {@inheritDoc}
     */
    protected $code = 40101;
}

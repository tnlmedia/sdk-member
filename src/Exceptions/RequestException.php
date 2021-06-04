<?php

namespace TNLMedia\MemberSDK\Exceptions;

/**
 * Class RequestException
 * @package TNLMedia\MemberSDK\Exceptions
 * @method static RequestException invalidField(string $hint = '')
 */
class RequestException extends Exception
{
    /**
     * {@inheritDoc}
     */
    protected $code = 50000;
}

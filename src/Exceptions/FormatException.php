<?php

namespace TNLMedia\MemberSDK\Exceptions;

/**
 * Class FormatException
 * @package TNLMedia\MemberSDK\Exceptions
 * @method static FormatException invalidField(string $hint = '')
 */
class FormatException extends Exception
{
    /**
     * {@inheritDoc}
     */
    protected $code = 40002;
}

<?php

namespace TNLMedia\MemberSDK\Exceptions;

/**
 * Class UnnecessaryException
 * @package TNLMedia\MemberSDK\Exceptions
 * @method static UnnecessaryException invalidField(string $hint = '')
 */
class UnnecessaryException extends Exception
{
    /**
     * {@inheritDoc}
     */
    protected $code = 40004;
}

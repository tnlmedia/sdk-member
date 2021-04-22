<?php

namespace TNLMedia\MemberSDK\Exceptions;

/**
 * Class DuplicateException
 * @package TNLMedia\MemberSDK\Exceptions
 * @method static DuplicateException invalidField(string $hint = '')
 */
class DuplicateException extends Exception
{
    /**
     * {@inheritDoc}
     */
    protected $code = 40003;
}

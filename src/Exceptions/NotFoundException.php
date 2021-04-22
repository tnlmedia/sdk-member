<?php

namespace TNLMedia\MemberSDK\Exceptions;

/**
 * Class NotFoundException
 * @package TNLMedia\MemberSDK\Exceptions
 * @method static NotFoundException invalidField(string $hint = '')
 */
class NotFoundException extends Exception
{
    /**
     * {@inheritDoc}
     */
    protected $code = 40401;
}

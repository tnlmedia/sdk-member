<?php

namespace TNLMedia\MemberSDK\Exceptions;

/**
 * Class UploadException
 * @package TNLMedia\MemberSDK\Exceptions
 * @method static UploadException invalidField(string $hint = '')
 */
class UploadException extends Exception
{
    /**
     * {@inheritDoc}
     */
    protected $code = 50001;
}

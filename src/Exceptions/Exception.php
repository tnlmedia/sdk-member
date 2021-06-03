<?php

namespace TNLMedia\MemberSDK\Exceptions;

use Exception as ExceptionBase;
use Throwable;

/**
 * Class Exception
 * @package TNLMedia\MemberSDK\Exceptions
 * @see https://member.tnlmedia.com/docs/#/v1/response
 */
class Exception extends ExceptionBase
{
    /**
     * {@inheritDoc}
     *
     * Part: 000(HTTP Status)-00(Serial)
     */
    protected $code = 50000;

    /**
     * Field hint
     *
     * @var string
     */
    protected $hint = '';

    /**
     * {@inheritDoc}
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null, string $hint = '')
    {
        parent::__construct($message, $code, $previous);

        $this->hint = $hint;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message = '')
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Set hint field
     *
     * @param string $hint
     * @return $this
     */
    public function setHint(string $hint = '')
    {
        $this->hint = $hint;
        return $this;
    }

    /**
     * Get hint field
     *
     * @return string
     */
    public function getHint()
    {
        return $this->hint;
    }

    /**
     * Exception on field
     *
     * @param string $hint
     * @return static
     */
    public static function invalidField(string $hint = '')
    {
        return new static('', 0, null, $hint);
    }
}

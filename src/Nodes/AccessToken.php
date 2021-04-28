<?php

namespace TNLMedia\MemberSDK\Nodes;

use DateTime;

class AccessToken extends Node
{
    /**
     * Convert to header string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getHeaderToken();
    }

    /**
     * {@inheritDoc}
     */
    public function initial(array $attributes = [])
    {
        $attributes['expire'] = time() + intval($attributes['expires_in'] ?? 0);
        return parent::initial($attributes);
    }

    /**
     * Token type
     *
     * @return string
     */
    public function getType()
    {
        return $this->getStringAttributes('token_type', 'Bearer');
    }

    /**
     * Expire time
     *
     * @return DateTime
     */
    public function getExpire()
    {
        /** @var DateTime $value */
        $value = new DateTime();
        $value->setTimestamp($this->getIntegerAttributes('expire'));
        return $value;
    }

    /**
     * Token string
     *
     * @return string
     */
    public function getToken()
    {
        return $this->getStringAttributes('access_token');
    }

    /**
     * Build header string
     *
     * @return string
     */
    public function getHeaderToken()
    {
        return $this->getType() . ' ' . $this->getToken();
    }

    /**
     * Scopes
     *
     * @return array
     */
    public function getScopes()
    {
        $this->requestDetail();

        $value = (array)$this->getAttributes('scopes', []);
        foreach ($value as $key => $item) {
            $value[$key] = strval($item);
        }
        return array_values($value);
    }

    /**
     * Console data
     *
     * @return array
     */
    public function getConsole()
    {
        $this->requestDetail();
        return $this->getArrayAttributes('console');
    }

    /**
     * Current console ID
     *
     * @return int
     */
    public function getConsoleID()
    {
        $this->requestDetail();
        return $this->getIntegerAttributes('console.console_id');
    }

    /**
     * User data
     *
     * @return array
     */
    public function getUser()
    {
        $this->requestDetail();
        return $this->getArrayAttributes('user');
    }

    /**
     * Current user ID
     *
     * @return int
     */
    public function getUserID()
    {
        $this->requestDetail();
        return $this->getIntegerAttributes('user.id');
    }

    /**
     * Current user mail
     *
     * @return string
     */
    public function getUserMail()
    {
        $this->requestDetail();
        return $this->getStringAttributes('user.mail');
    }

    /**
     * Current user avatar
     *
     * @return string
     */
    public function getUserAvatar()
    {
        $this->requestDetail();
        return $this->getStringAttributes('user.avatar');
    }

    /**
     * Check scope capability
     *
     * @param $require
     * @return bool
     */
    public function available($require)
    {
        $require = is_array($require) ? $require : [$require];

        $scopes = $this->getScopes();
        foreach ($require as $item) {
            if (in_array(strval($item), $scopes)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check token expire
     *
     * @return bool
     */
    public function isExpire()
    {
        if ($this->getExpire()->getTimestamp() <= time()) {
            return true;
        }
        return false;
    }

    /**
     * Request token detail
     *
     * @throws \TNLMedia\MemberSDK\Exceptions\AuthorizeException
     * @throws \TNLMedia\MemberSDK\Exceptions\DuplicateException
     * @throws \TNLMedia\MemberSDK\Exceptions\Exception
     * @throws \TNLMedia\MemberSDK\Exceptions\FormatException
     * @throws \TNLMedia\MemberSDK\Exceptions\NotFoundException
     * @throws \TNLMedia\MemberSDK\Exceptions\ProtectedException
     * @throws \TNLMedia\MemberSDK\Exceptions\RequestException
     * @throws \TNLMedia\MemberSDK\Exceptions\RequireException
     * @throws \TNLMedia\MemberSDK\Exceptions\UnnecessaryException
     * @throws \TNLMedia\MemberSDK\Exceptions\UploadException
     */
    protected function requestDetail()
    {
        if (array_key_exists('scopes', $this->attributes)) {
            return;
        }

        // Request
        $result = $this->core->request('token');

        // Update
        $this->initial($result + $this->attributes);
    }
}

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
        $value->setTimestamp($this->getIntegerAttributes('expires_in'));
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
        return $this->getArrayAttributes('console');
    }

    /**
     * Current console ID
     *
     * @return int
     */
    public function getConsoleID()
    {
        return $this->getIntegerAttributes('console.console_id');
    }

    /**
     * User data
     *
     * @return array
     */
    public function getUser()
    {
        return $this->getArrayAttributes('user');
    }

    /**
     * Current user ID
     *
     * @return int
     */
    public function getUserID()
    {
        return $this->getIntegerAttributes('user.id');
    }

    /**
     * Current user mail
     *
     * @return string
     */
    public function getUserMail()
    {
        return $this->getStringAttributes('user.mail');
    }

    /**
     * Current user avatar
     *
     * @return string
     */
    public function getUserAvatar()
    {
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
}

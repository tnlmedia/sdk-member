<?php

namespace TNLMedia\MemberSDK\Nodes;

use DateTime;

class AccessToken implements NodeInterface
{
    /**
     * Token attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * {@inheritDoc}
     */
    public function __construct(array $attributes = [])
    {
        $this->initial($attributes);
    }

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
        $this->attributes = [];

        // Basic
        $this->attributes['type'] = strval($attributes['token_type'] ?? 'Bearer');
        $this->attributes['expire'] = new DateTime();
        $this->attributes['expire'] = $this->attributes['expire']
            ->setTimestamp(intval($attributes['expires_in'] ?? ''));
        $this->attributes['token'] = strval($attributes['access_token'] ?? '');

        // Scopes
        $this->attributes['scopes'] = $attributes['scopes'] ?? [];
        $this->attributes['scopes'] = is_array($this->attributes['scopes']) ? $this->attributes['scopes'] : [];
        foreach ($this->attributes['scopes'] as $key => $value) {
            $this->attributes['scopes'][$key] = strval($value);
        }
        $this->attributes['scopes'] = array_values($this->attributes['scopes']);

        // Console
        $this->attributes['console'] = $attributes['console'] ?? [];

        // User
        $this->attributes['user'] = $attributes['user'] ?? [];

        return $this;
    }

    /**
     * Token type
     *
     * @return string
     */
    public function getType()
    {
        return strval($this->attributes['type'] ?? 'Bearer');
    }

    /**
     * Expire time
     *
     * @return DateTime
     */
    public function getExpire()
    {
        return $this->attributes['expire'] ?? new DateTime();
    }

    /**
     * Token string
     *
     * @return string
     */
    public function getToken()
    {
        return strval($this->attributes['token'] ?? '');
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
        return (array)$this->attributes['scopes'];
    }

    /**
     * Console data
     *
     * @return array
     */
    public function getConsole()
    {
        return (array)$this->attributes['console'];
    }

    /**
     * Current console ID
     *
     * @return int
     */
    public function getConsoleID()
    {
        return intval($this->attributes['console']['console_id'] ?? 0);
    }

    /**
     * User data
     *
     * @return array
     */
    public function getUser()
    {
        return (array)$this->attributes['user'];
    }

    /**
     * Current user ID
     *
     * @return int
     */
    public function getUserID()
    {
        return intval($this->attributes['user']['id'] ?? 0);
    }

    /**
     * Current user mail
     *
     * @return string
     */
    public function getUserMail()
    {
        return strval($this->attributes['user']['mail'] ?? '');
    }

    /**
     * Current user avatar
     *
     * @return string
     */
    public function getUserAvatar()
    {
        return strval($this->attributes['user']['avatar'] ?? '');
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

<?php

namespace TNLMedia\MemberSDK\Nodes;

use DateTime;
use Throwable;
use TNLMedia\MemberSDK\MemberSDK;

class AccessToken extends Node
{
    /**
     * Require loaded
     *
     * @var array
     */
    protected $requires = [];

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
        return $this->getDateTimeAttributes('expire');
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
        $this->requireDetail();

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
        $this->requireDetail();
        return $this->getArrayAttributes('console');
    }

    /**
     * Current console ID
     *
     * @return int
     */
    public function getConsoleID()
    {
        $this->requireDetail();
        return $this->getIntegerAttributes('console.console_id');
    }

    /**
     * User node
     *
     * @return User|null
     */
    public function getUser()
    {
        $this->requireUser();
        /** @var User|null $user */
        $user = $this->getRelations('user');
        return $user;
    }

    /**
     * Current user ID
     *
     * @return int
     */
    public function getUserID()
    {
        $this->requireDetail();
        return $this->getIntegerAttributes('user.id');
    }

    /**
     * Current user name
     *
     * @return string
     */
    public function getUserName()
    {
        $this->requireDetail();
        return $this->getStringAttributes('user.nickname');
    }

    /**
     * Current user avatar
     *
     * @return string
     */
    public function getUserAvatar()
    {
        $this->requireDetail();
        return $this->getStringAttributes('user.avatar');
    }

    /**
     * Current user mail
     *
     * @return string
     */
    public function getUserMail()
    {
        $this->requireDetail();
        return $this->getStringAttributes('user.mail.value');
    }

    /**
     * Current user language
     *
     * @return string
     */
    public function getUserLanguage()
    {
        $this->requireDetail();
        return $this->getStringAttributes('user.language');
    }

    /**
     * Current user timezone
     *
     * @return string
     */
    public function getUserTimezone()
    {
        $this->requireDetail();
        return $this->getStringAttributes('user.timezone');
    }

    /**
     * Current user currency
     *
     * @return string
     */
    public function getUserCurrency()
    {
        $this->requireDetail();
        return $this->getStringAttributes('user.currency');
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
     * Current user mail is verified
     *
     * @return bool
     */
    public function isUserMailVerify()
    {
        return $this->getBooleanAttributes('user.mail.verify');
    }

    /**
     * Current user is enabled
     *
     * @return bool
     */
    public function isUserEnable()
    {
        return $this->getBooleanAttributes('user.status');
    }

    /**
     * Load token detail
     */
    protected function requireDetail()
    {
        // Check available
        if (!$this->core instanceof MemberSDK) {
            return;
        }
        if (array_key_exists('scopes', $this->attributes)) {
            return;
        }
        if (array_key_exists('detail', $this->requires)) {
            return;
        }
        $this->requires['detail'] = true;

        // Request
        try {
            $result = $this->core->request('token');
        } catch (Throwable $e) {
            return;
        }

        // Update
        $this->initial($result + $this->attributes);
    }

    /**
     * Load token user
     */
    protected function requireUser()
    {
        // Check available
        if (!$this->core instanceof MemberSDK) {
            return;
        }
        if (array_key_exists('user', $this->relations)) {
            return;
        }
        $this->setRelations('user');

        // Request
        try {
            $result = $this->core->request('users/me');
        } catch (Throwable $e) {
            return;
        }

        // Build
        $user = new User($result, $this->core);
        $this->setRelations('user', $user);
    }
}

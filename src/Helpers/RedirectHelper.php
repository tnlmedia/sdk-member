<?php

namespace TNLMedia\MemberSDK\Helpers;

class RedirectHelper extends Helper
{
    /**
     * Login page
     *
     * @param string|null $tips
     * @param string|null $state
     * @param string|null $redirect_uri
     * @param array $scopes
     * @return string
     */
    public function login(string $tips = null, string $state = null, string $redirect_uri = null, array $scopes = [])
    {
        $query = [];
        $query['redirect_uri'] = $redirect_uri ?? $this->core->getDefaultRedirect();
        if (!empty($scopes)) {
            $query['scope'] = implode(' ', $scopes);
        }
        if ($state !== null) {
            $query['state'] = $state;
        }
        if ($tips !== null) {
            $query['tips'] = $tips;
        }
        return $this->buildUrl('/', $query);
    }

    /**
     * Logout member system
     *
     * @param string|null $redirect_uri
     * @return string
     */
    public function logout(string $redirect_uri = null)
    {
        $query = [];
        if (!empty($redirect_uri)) {
            $query['redirect_uri'] = $redirect_uri;
        }
        return $this->buildUrl('logout', $query);
    }

    /**
     * Announce page
     *
     * @return string
     */
    public function pageAnnounce()
    {
        return $this->buildUrl('announce');
    }

    /**
     * Profile page
     *
     * @return string
     */
    public function pageProfile()
    {
        return $this->buildUrl('profile');
    }

    /**
     * Security page
     *
     * @return string
     */
    public function pageSecurity()
    {
        return $this->buildUrl('security');
    }

    /**
     * Privacy page
     *
     * @return string
     */
    public function pagePrivacy()
    {
        return $this->buildUrl('privacy');
    }

    /**
     * Subscription page
     *
     * @return string
     */
    public function pageSubscription()
    {
        return $this->buildUrl('subscription');
    }

    /**
     * Purchase a subscription
     *
     * @param int $plan_id
     * @return string
     */
    public function purchaseSubscription(int $plan_id)
    {
        $query = [];
        if (!empty($plan_id)) {
            $query['plan_id'] = $plan_id;
        }
        return $this->buildUrl('subscription/forward', $query);
    }

    /**
     * Build website url
     *
     * @param string $path
     * @param array $query
     * @return string
     */
    protected function buildUrl(string $path, array $query = [])
    {
        $query['console_id'] = $query['console_id'] ?? $this->core->getConsoleID();
        if (empty($query['console_id'])) {
            unset($query['console_id']);
        }
        $query['client_id'] = $query['client_id'] ?? $this->core->getClientID();
        if (empty($query['client_id'])) {
            unset($query['client_id']);
        }

        $url = 'https://' . $this->core->getHost();
        $url .= '/' . trim($path, '/');
        if (!empty($query)) {
            $url .= '?' . http_build_query($query, '', '&');
        }
        return $url;
    }
}

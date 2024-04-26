<?php

namespace TNLMedia\MemberSDK\Clients;

use TNLMedia\MemberSDK\Nodes\AccessToken;

class AuthorizeClient extends Client
{
    /**
     * Request access token from auth code
     *
     * @param string $code
     * @param string $redirect_uri
     * @return AccessToken
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
     * @see https://member.inkmaginecms.com/docs/#/v1/auth/token-code
     */
    public function authCode(string $code, string $redirect_uri)
    {
        // Request
        $parameters = [];
        $parameters['grant_type'] = 'authorization_code';
        $parameters['client_id'] = $this->core->getClientID();
        $parameters['client_secret'] = $this->core->getClientSecret();
        $parameters['redirect_uri'] = $redirect_uri;
        $parameters['code'] = $code;
        $result = $this->core->request('token', $parameters, 'POST');

        // Build
        $token = new AccessToken($result, $this->core);

        // Put core
        $this->core->setToken($token);
        return $token;
    }

    /**
     * Request access token only for API
     *
     * @param array $scopes
     * @return AccessToken
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
     * @see https://member.inkmaginecms.com/docs/#/v1/auth/token-credentials
     */
    public function credential(array $scopes = [])
    {
        // Request
        $parameters = [];
        $parameters['grant_type'] = 'client_credentials';
        $parameters['client_id'] = $this->core->getClientID();
        $parameters['client_secret'] = $this->core->getClientSecret();
        if (!empty($scopes)) {
            $parameters['scope'] = implode(' ', $scopes);
        }
        $console_id = $this->core->getConsoleID();
        if (!empty($console_id)) {
            $parameters['console_id'] = $console_id;
        }
        $result = $this->core->request('token', $parameters, 'POST');

        // Build
        $token = new AccessToken($result, $this->core);

        // Put core
        $this->core->setToken($token);
        return $token;
    }
}

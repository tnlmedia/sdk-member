<?php

namespace TNLMedia\MemberSDK\Clients;

use TNLMedia\MemberSDK\Nodes\Flag;
use TNLMedia\MemberSDK\Nodes\SearchResult;
use TNLMedia\MemberSDK\Nodes\User;

class FlagClient extends Client
{
    /**
     * Search flag in console
     *
     * @param array $filters
     * @param string|null $sort
     * @param int $offset
     * @param int $limit
     * @return SearchResult
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
     * @see https://member.inkmaginecms.com/docs/#/v1/flag/search
     */
    public function search(array $filters = [], string $sort = null, int $offset = 0, int $limit = 10)
    {
        // Request
        $parameters = $filters;
        if (!empty($sort)) {
            $parameters['sort'] = $sort;
        }
        if (!empty($offset)) {
            $parameters['offset'] = $offset;
        }
        $parameters['limit'] = $limit;
        $result = $this->core->request('flags', $parameters);

        // Process result
        foreach ($result['list'] as $key => $item) {
            $result['list'][$key] = new Flag($item, $this->core);
        }
        $result = new SearchResult($result, $this->core);

        return $result;
    }

    /**
     * Add flag to user
     *
     * @param int $user_id
     * @param string $name
     * @return User
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
     * @see https://member.inkmaginecms.com/docs/#/v1/user/flag-set
     */
    public function setFlag(int $user_id, string $name)
    {
        // Request
        $parameters = [];
        $parameters['name'] = $name;
        $result = $this->core->request('users/' . $user_id . '/flag', $parameters, 'POST');

        // Build
        $user = new User($result, $this->core);
        return $user;
    }

    /**
     * Remove flag from user
     *
     * @param int $user_id
     * @param string $name
     * @return User
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
     * @see https://member.inkmaginecms.com/docs/#/v1/user/flag-unset
     */
    public function removeFlag(int $user_id, string $name)
    {
        // Request
        $parameters = [];
        $parameters['name'] = $name;
        $result = $this->core->request('users/' . $user_id . '/flag', $parameters, 'DELETE');

        // Build
        $user = new User($result, $this->core);
        return $user;
    }
}

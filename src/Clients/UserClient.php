<?php

namespace TNLMedia\MemberSDK\Clients;

use TNLMedia\MemberSDK\Nodes\SearchResult;
use TNLMedia\MemberSDK\Nodes\User;

class UserClient extends Client
{
    /**
     * Search user from filters
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
        $result = $this->core->request('users', $parameters);

        // Process result
        foreach ($result['list'] as $key => $item) {
            $result['list'][$key] = new User($item, $this->core);
        }
        $result = new SearchResult($result, $this->core);

        return $result;
    }

    /**
     * Get target user
     *
     * @param int $user_id
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
     */
    public function get(int $user_id)
    {
        // Request
        $result = $this->core->request('users/' . $user_id);

        // Build
        $user = new User($result, $this->core);
        return $user;
    }

    /**
     * Update target user status in console
     *
     * @param int $user_id
     * @param int $status
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
     */
    public function updateStatus(int $user_id, int $status)
    {
        // Request
        $parameters = [];
        $parameters['status'] = $status;
        $result = $this->core->request('users/' . $user_id . '/status', $parameters, 'PATCH');

        // Build
        $user = new User($result, $this->core);
        return $user;
    }
}

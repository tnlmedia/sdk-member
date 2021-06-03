<?php

namespace TNLMedia\MemberSDK\Clients;

use TNLMedia\MemberSDK\Contents\ServicePeriodConstants;
use TNLMedia\MemberSDK\Nodes\SearchResult;
use TNLMedia\MemberSDK\Nodes\Service;
use TNLMedia\MemberSDK\Nodes\User;

class ServiceClient extends Client
{
    /**
     * Search service in console
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
     * @see https://member.tnlmedia.com/docs/#/v1/service/service-search
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
        $result = $this->core->request('services', $parameters);

        // Process result
        foreach ($result['list'] as $key => $item) {
            $result['list'][$key] = new Service($item, $this->core);
        }
        $result = new SearchResult($result, $this->core);

        return $result;
    }

    /**
     * Build new service
     *
     * @param string $name
     * @param array $attributes
     * @return Service
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
     * @see https://member.tnlmedia.com/docs/#/v1/service/service-create
     */
    public function create(string $name, array $attributes = [])
    {
        // Request
        $parameters = $attributes;
        $parameters['name'] = $name;
        $result = $this->core->request('services', $parameters, 'POST');

        // Build
        $service = new Service($result, $this->core);
        return $service;
    }

    /**
     * Update exists service
     *
     * @param int $service_id
     * @param array $attributes
     * @return Service
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
     * @see https://member.tnlmedia.com/docs/#/v1/service/service-update
     */
    public function update(int $service_id, array $attributes = [])
    {
        // Request
        $parameters = $attributes;
        $result = $this->core->request('services/' . $service_id, $parameters, 'PATCH');

        // Build
        $service = new Service($result, $this->core);
        return $service;
    }

    /**
     * Remove useless service
     *
     * @param int $service_id
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
     * @see https://member.tnlmedia.com/docs/#/v1/service/service-delete
     */
    public function remove(int $service_id)
    {
        $this->core->request('services/' . $service_id, [], 'DELETE');
    }

    /**
     * Extend user service expire
     *
     * @param int $user_id
     * @param int $service_id
     * @param int $length
     * @param string $type
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
     * @see https://member.tnlmedia.com/docs/#/v1/user/service-extend
     */
    public function extend(int $user_id, int $service_id, int $length = 1, string $type = ServicePeriodConstants::MONTH)
    {
        // Request
        $parameters = [];
        $parameters['service'] = $service_id;
        $parameters['type'] = $type;
        $parameters['length'] = $length;
        $result = $this->core->request('users/' . $user_id . '/service', $parameters, 'POST');

        // Build
        $user = new User($result, $this->core);
        return $user;
    }
}

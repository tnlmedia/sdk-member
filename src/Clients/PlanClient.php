<?php

namespace TNLMedia\MemberSDK\Clients;

use TNLMedia\MemberSDK\Nodes\Plan;
use TNLMedia\MemberSDK\Nodes\SearchResult;

class PlanClient extends Client
{
    /**
     * Search plan in service
     *
     * @param int $service_id
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
     * @see https://member.tnlmedia.com/docs/#/v1/charge/plan-search
     */
    public function search(int $service_id, array $filters = [], string $sort = null, int $offset = 0, int $limit = 10)
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
        $result = $this->core->request('services/' . $service_id . '/plans', $parameters);

        // Process result
        foreach ($result['list'] as $key => $item) {
            $result['list'][$key] = new Plan($item, $this->core);
        }
        $result = new SearchResult($result, $this->core);

        return $result;
    }

    /**
     * Build new plan for service
     *
     * @param int $service_id
     * @param string $name
     * @param array $attributes
     * @return Plan
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
     * @see https://member.tnlmedia.com/docs/#/v1/charge/plan-create
     */
    public function create(int $service_id, string $name, array $attributes = [])
    {
        // Request
        $parameters = $attributes;
        $parameters['name'] = $name;
        $result = $this->core->request('services/' . $service_id . '/plans', $parameters, 'POST');

        // Build
        $plan = new Plan($result, $this->core);
        return $plan;
    }

    /**
     * Update exists plan
     *
     * @param int $service_id
     * @param int $plan_id
     * @param array $attributes
     * @return Plan
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
     * @see https://member.tnlmedia.com/docs/#/v1/charge/plan-update
     */
    public function update(int $service_id, int $plan_id, array $attributes = [])
    {
        // Request
        $parameters = $attributes;
        $result = $this->core->request('services/' . $service_id . '/plans/' . $plan_id, $parameters, 'PATCH');

        // Build
        $plan = new Plan($result, $this->core);
        return $plan;
    }

    /**
     * Remove useless plan
     *
     * @param int $service_id
     * @param int $plan_id
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
     * @see https://member.tnlmedia.com/docs/#/v1/charge/plan-delete
     */
    public function remove(int $service_id, int $plan_id)
    {
        $this->core->request('services/' . $service_id . '/plans/' . $plan_id, [], 'DELETE');
    }
}

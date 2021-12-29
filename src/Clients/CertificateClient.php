<?php

namespace TNLMedia\MemberSDK\Clients;

use TNLMedia\MemberSDK\Nodes\Certificate;
use TNLMedia\MemberSDK\Nodes\SearchResult;

class CertificateClient extends Client
{
    /**
     * Search certificate in console
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
     * @see https://member.tnlmedia.com/docs/#/v1/charge/certificate-search
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
        $result = $this->core->request('certificates', $parameters);

        // Process result
        foreach ($result['list'] as $key => $item) {
            $result['list'][$key] = new Certificate($item, $this->core);
        }
        $result = new SearchResult($result, $this->core);

        return $result;
    }

    /**
     * Build new certificate
     *
     * @param string $slug
     * @param string $name
     * @param array $attributes
     * @return Certificate
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
     * @see https://member.tnlmedia.com/docs/#/v1/charge/certificate-create
     */
    public function create(string $slug, string $name, array $attributes = [])
    {
        // Request
        $parameters = $attributes;
        $parameters['slug'] = $slug;
        $parameters['name'] = $name;
        $result = $this->core->request('certificates', $parameters, 'POST');

        // Build
        $certificate = new Certificate($result, $this->core);
        return $certificate;
    }

    /**
     * Update exists certificate
     *
     * @param int $certificate_id
     * @param array $attributes
     * @return Certificate
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
     * @see https://member.tnlmedia.com/docs/#/v1/charge/certificate-update
     */
    public function update(int $certificate_id, array $attributes = [])
    {
        // Request
        $parameters = $attributes;
        $result = $this->core->request('certificates/' . $certificate_id, $parameters, 'PATCH');

        // Build
        $certificate = new Certificate($result, $this->core);
        return $certificate;
    }

    /**
     * Remove useless certificate
     *
     * @param int $certificate_id
     * @return void
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
     * @see https://member.tnlmedia.com/docs/#/v1/charge/certificate-delete
     */
    public function remove(int $certificate_id)
    {
        $this->core->request('certificates/' . $certificate_id, [], 'DELETE');
    }
}

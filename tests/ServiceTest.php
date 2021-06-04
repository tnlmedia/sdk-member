<?php

namespace TNLMedia\MemberSDK\Tests;

use ArrayIterator;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use TNLMedia\MemberSDK\Constants\ServiceStatusConstants;
use TNLMedia\MemberSDK\MemberSDK;
use TNLMedia\MemberSDK\Nodes\AccessToken;
use TNLMedia\MemberSDK\Nodes\Service;
use TNLMedia\MemberSDK\Nodes\User;

class ServiceTest extends TestCase
{
    /**
     * Build SDK
     *
     * @return MemberSDK
     */
    public function testSdk()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        // Build SDK
        $config = [];
        $config['console_id'] = $_ENV['CONSOLE_ID'];
        $config['client_id'] = $_ENV['CLIENT_ID'];
        $config['client_secret'] = $_ENV['CLIENT_SECRET'];
        $config['redirect_uri'] = $_ENV['REDIRECT_URI'];
        $sdk = new MemberSDK($config);
        $sdk->useStage();
        $this->assertInstanceOf(MemberSDK::class, $sdk);

        // Request token
        $sdk->authorize->credential();
        $this->assertInstanceOf(AccessToken::class, $sdk->getToken());

        return $sdk;
    }

    /**
     * Search service
     *
     * @depends testSdk
     * @param MemberSDK $sdk
     * @return array
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
    public function testSearch(MemberSDK $sdk)
    {
        $result = $sdk->service->search([], null, 0, 1);
        $this->assertInstanceOf(ArrayIterator::class, $result->getList());
        foreach ($result->getList() as $service) {
            $this->assertInstanceOf(Service::class, $service);
            $this->assertNotEmpty($service->getName());
        }
        $this->assertEquals(1, $result->getCount());
        $this->assertGreaterThan(0, $result->getTotal());

        return [
            'sdk' => $sdk,
            'service' => $service,
        ];
    }

    /**
     * Extend user
     *
     * @depends testSearch
     * @param array $source
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
    public function testExtend(array $source)
    {
        /** @var MemberSDK $sdk */
        $sdk = $source['sdk'];
        /** @var Service $service */
        $service = $source['service'];

        $user = $sdk->service->extend($_ENV['USER_ID'], $service->getId());
        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($user->hasService($service->getId()));
    }

    /**
     * New service and update, clear it.
     *
     * @depends testSdk
     * @param MemberSDK $sdk
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
    public function testNew(MemberSDK $sdk)
    {
        // Create
        $service = $sdk->service->create('Test service');
        $this->assertInstanceOf(Service::class, $service);
        $this->assertFalse($service->isEnable());

        // Update
        $service = $sdk->service->update($service->getId(), ['status' => ServiceStatusConstants::ENABLED]);
        $this->assertInstanceOf(Service::class, $service);
        $this->assertTrue($service->isEnable());

        // Delete
        $sdk->service->remove($service->getId());
    }
}

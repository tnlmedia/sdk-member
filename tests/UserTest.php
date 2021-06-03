<?php

namespace TNLMedia\MemberSDK\Tests;

use ArrayIterator;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use TNLMedia\MemberSDK\Contents\UserStatusConstants;
use TNLMedia\MemberSDK\MemberSDK;
use TNLMedia\MemberSDK\Nodes\AccessToken;
use TNLMedia\MemberSDK\Nodes\User;

class UserTest extends TestCase
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
        $this->assertTrue($sdk instanceof MemberSDK);

        // Request token
        $sdk->authorize->credential();
        $this->assertTrue($sdk->getToken() instanceof AccessToken);

        return $sdk;
    }

    /**
     * Search user
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
    public function testSearch(MemberSDK $sdk)
    {
        $result = $sdk->user->search([], null, 0, 1);
        $this->assertTrue($result->getList() instanceof ArrayIterator);
        foreach ($result->getList() as $user) {
            $this->assertTrue($user instanceof User);
            $this->assertNotEmpty($user->getMobileCode());
        }
        $this->assertEquals(1, $result->getCount());
        $this->assertGreaterThan(0, $result->getTotal());
    }

    /**
     * Get user
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
    public function testGet(MemberSDK $sdk)
    {
        $user = $sdk->user->get(intval($_ENV['USER_ID']));
        $this->assertTrue($user instanceof User);
    }

    /**
     * Update user status
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
    public function testUpdateStatus(MemberSDK $sdk)
    {
        $user = $sdk->user->updateStatus(intval($_ENV['USER_ID']), UserStatusConstants::DISABLED);
        $this->assertFalse($user->isEnable());

        $user = $sdk->user->updateStatus(intval($_ENV['USER_ID']), UserStatusConstants::ENABLED);
        $this->assertTrue($user->isEnable());
    }
}

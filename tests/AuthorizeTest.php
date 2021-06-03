<?php

namespace TNLMedia\MemberSDK\Tests;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use TNLMedia\MemberSDK\Exceptions\Exception;
use TNLMedia\MemberSDK\MemberSDK;
use TNLMedia\MemberSDK\Nodes\AccessToken;

class AuthorizeTest extends TestCase
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

        return $sdk;
    }

    /**
     * Auth code token
     *
     * @depends testSdk
     * @param MemberSDK $sdk
     * @return MemberSDK
     * @throws Exception
     * @throws \TNLMedia\MemberSDK\Exceptions\AuthorizeException
     * @throws \TNLMedia\MemberSDK\Exceptions\DuplicateException
     * @throws \TNLMedia\MemberSDK\Exceptions\FormatException
     * @throws \TNLMedia\MemberSDK\Exceptions\NotFoundException
     * @throws \TNLMedia\MemberSDK\Exceptions\ProtectedException
     * @throws \TNLMedia\MemberSDK\Exceptions\RequestException
     * @throws \TNLMedia\MemberSDK\Exceptions\RequireException
     * @throws \TNLMedia\MemberSDK\Exceptions\UnnecessaryException
     * @throws \TNLMedia\MemberSDK\Exceptions\UploadException
     */
    public function testAuthCode(MemberSDK $sdk)
    {
        $this->expectException(Exception::class);
        $sdk->authorize->authCode('test', getenv('REDIRECT_URI'));
        return $sdk;
    }

    /**
     * Credential token
     *
     * @depends testSdk
     * @param MemberSDK $sdk
     * @return MemberSDK
     * @throws Exception
     * @throws \TNLMedia\MemberSDK\Exceptions\AuthorizeException
     * @throws \TNLMedia\MemberSDK\Exceptions\DuplicateException
     * @throws \TNLMedia\MemberSDK\Exceptions\FormatException
     * @throws \TNLMedia\MemberSDK\Exceptions\NotFoundException
     * @throws \TNLMedia\MemberSDK\Exceptions\ProtectedException
     * @throws \TNLMedia\MemberSDK\Exceptions\RequestException
     * @throws \TNLMedia\MemberSDK\Exceptions\RequireException
     * @throws \TNLMedia\MemberSDK\Exceptions\UnnecessaryException
     * @throws \TNLMedia\MemberSDK\Exceptions\UploadException
     */
    public function testCredential(MemberSDK $sdk)
    {
        $sdk->authorize->credential();
        $this->assertTrue($sdk->getToken() instanceof AccessToken);
        return $sdk;
    }

    /**
     * Token by string
     *
     * @depends testCredential
     * @param MemberSDK $sdk
     * @return MemberSDK
     */
    public function testTokenString(MemberSDK $sdk)
    {
        $sdk->setTokenString($sdk->getToken()->getToken());
        $this->assertTrue($sdk->getToken() instanceof AccessToken);
        return $sdk;
    }

    /**
     * Token detail
     *
     * @depends testCredential
     * @param MemberSDK $sdk
     */
    public function testTokenDetail(MemberSDK $sdk)
    {
        $this->assertGreaterThan(0, count($sdk->getToken()->getScopes()));
    }

    /**
     * Token user
     *
     * @depends testCredential
     * @param MemberSDK $sdk
     */
    public function testTokenUser(MemberSDK $sdk)
    {
        $this->assertNull($sdk->getToken()->getUser());
    }
}

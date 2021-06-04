<?php

namespace TNLMedia\MemberSDK\Tests;

use ArrayIterator;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use TNLMedia\MemberSDK\Contents\FlagTypeConstants;
use TNLMedia\MemberSDK\MemberSDK;
use TNLMedia\MemberSDK\Nodes\AccessToken;
use TNLMedia\MemberSDK\Nodes\Flag;
use TNLMedia\MemberSDK\Nodes\User;

class FlagTest extends TestCase
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
     * Flag user
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
    public function testFlagUser(MemberSDK $sdk)
    {
        // Flag
        $user = $sdk->flag->setFlag($_ENV['USER_ID'], 'Test flag');
        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($user->hasFlag('Test flag'));

        // Unflag
        $user = $sdk->flag->removeFlag($_ENV['USER_ID'], 'Test flag');
        $this->assertInstanceOf(User::class, $user);
        $this->assertFalse($user->hasFlag('Test flag'));

        return $sdk;
    }

    /**
     * Search flag
     *
     * @depends testFlagUser
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
        $result = $sdk->flag->search(['type' => FlagTypeConstants::CUSTOM], null, 0, 1);
        $this->assertInstanceOf(ArrayIterator::class, $result->getList());
        foreach ($result->getList() as $flag) {
            $this->assertInstanceOf(Flag::class, $flag);
            $this->assertEquals(FlagTypeConstants::CUSTOM, $flag->getType());
        }
        $this->assertEquals(1, $result->getCount());
        $this->assertGreaterThan(0, $result->getTotal());
    }
}

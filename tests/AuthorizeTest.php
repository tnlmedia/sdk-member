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
     * @var MemberSDK
     */
    protected $sdk;

    /**
     * AuthorizeTest constructor.
     *
     * @param string|null $namei
     * @param array $data
     * @param string $dataName
     */
    public function __construct(string $name = null, array $data = [], $dataName = '')
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        // Build SDK
        $config = [];
        $config['environment'] = 'stage';
        $config['console_id'] = $_ENV['CONSOLE_ID'];
        $config['client_id'] = $_ENV['CLIENT_ID'];
        $config['client_secret'] = $_ENV['CLIENT_SECRET'];
        $config['redirect_uri'] = $_ENV['REDIRECT_URI'];
        $this->sdk = new MemberSDK($config);

        parent::__construct($name, $data, $dataName);
    }

    public function testAuthCode()
    {
        $this->expectException(Exception::class);
        $this->sdk->authorize->authCode('test', getenv('REDIRECT_URI'));
        sleep(1);
    }

    public function testCredential()
    {
        $token = $this->sdk->authorize->credential();
        $this->assertContainsOnlyInstancesOf(AccessToken::class, [$token]);
        sleep(1);
        var_dump($token);
        return $token;
    }

    public function testTokenString($token = null)
    {
        var_dump($token);
//        $token = $this->sdk->setTokenString($token->getToken());
        sleep(1);
    }
}

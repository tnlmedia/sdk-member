<?php

namespace TNLMedia\MemberSDK\Tests;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use TNLMedia\MemberSDK\MemberSDK;

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
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        // Build SDK
        $config = [];
        $config['environment'] = 'stage';
        $config['console_id'] = getenv('CONSOLE_ID');
        $config['client_id'] = getenv('CLIENT_ID');
        $config['client_secret'] = getenv('CLIENT_SECRET');
        $config['redirect_uri'] = getenv('REDIRECT_URI');
        $this->sdk = new MemberSDK($config);

        parent::__construct($name, $data, $dataName);
    }
}

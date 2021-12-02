<?php

namespace TNLMedia\MemberSDK\Tests;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use TNLMedia\MemberSDK\Constants\ScopeConstants;
use TNLMedia\MemberSDK\MemberSDK;
use TNLMedia\MemberSDK\Nodes\AccessToken;

class RedirectTest extends TestCase
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
     * @depends testSdk
     * @param MemberSDK $sdk
     */
    public function testUrl(MemberSDK $sdk)
    {
        $client_query = 'client_id=' . $sdk->getClientID();

        $url = $sdk->redirect->authorize($_ENV['REDIRECT_URI'], [
            'scope' => [
                ScopeConstants::USER_BASIC,
            ],
            'state' => 'RedirectTest',
        ]);
        $this->assertStringContainsString($client_query, $url);
        $this->assertStringContainsString('scope=' . ScopeConstants::USER_BASIC, $url);
        $this->assertStringContainsString('state=RedirectTest', $url);

        $url = $sdk->redirect->logout();
        $this->assertStringContainsString($client_query, $url);
        $this->assertStringContainsString('/logout', $url);

        $url = $sdk->redirect->pageAnnounce();
        $this->assertStringContainsString($client_query, $url);
        $this->assertStringContainsString('/announce', $url);

        $url = $sdk->redirect->pageProfile();
        $this->assertStringContainsString($client_query, $url);
        $this->assertStringContainsString('/profile', $url);

        $url = $sdk->redirect->pageSecurity();
        $this->assertStringContainsString($client_query, $url);
        $this->assertStringContainsString('/security', $url);

        $url = $sdk->redirect->pagePrivacy();
        $this->assertStringContainsString($client_query, $url);
        $this->assertStringContainsString('/privacy', $url);

        $url = $sdk->redirect->pageSubscription();
        $this->assertStringContainsString($client_query, $url);
        $this->assertStringContainsString('/subscription', $url);

        $url = $sdk->redirect->purchaseSubscription(['service' => 1]);
        $this->assertStringContainsString($client_query, $url);
        $this->assertStringContainsString('/subscription/forward', $url);

        $url = $sdk->redirect->purchaseCertificate(['certificate' => 1]);
        $this->assertStringContainsString($client_query, $url);
        $this->assertStringContainsString('/product/forward', $url);
    }
}

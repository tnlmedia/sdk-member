<?php

namespace TNLMedia\MemberSDK\Tests;

use ArrayIterator;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use TNLMedia\MemberSDK\Constants\ServiceStatusConstants;
use TNLMedia\MemberSDK\MemberSDK;
use TNLMedia\MemberSDK\Nodes\AccessToken;
use TNLMedia\MemberSDK\Nodes\Plan;

class PlanTest extends TestCase
{
    const SERVICE_ID = 1;

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
     * Search plan
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
        $result = $sdk->plan->search(self::SERVICE_ID, [], null, 0, 1);
        $this->assertInstanceOf(ArrayIterator::class, $result->getList());
        foreach ($result->getList() as $plan) {
            $this->assertInstanceOf(Plan::class, $plan);
            $this->assertNotEmpty($plan->getName());
        }
        $this->assertEquals(1, $result->getCount());
        $this->assertGreaterThan(0, $result->getTotal());
    }

    /**
     * New plan and update, clear it.
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
        $slug = 'test.' . time();
        $plan = $sdk->plan->create(self::SERVICE_ID, 'Test plan', [
            'slug' => $slug,
        ]);
        $this->assertInstanceOf(Plan::class, $plan);
        $this->assertEquals($slug, $plan->getSlug());
        $this->assertEquals('TWD', $plan->getCurrency());
        $this->assertEquals(0, $plan->getPrice());
        $this->assertFalse($plan->isRecurring());
        $this->assertFalse($plan->isVisible());
        $this->assertFalse($plan->isEnable());

        // Update
        $plan = $sdk->plan->update(self::SERVICE_ID, $plan->getId(), ['status' => ServiceStatusConstants::ENABLED]);
        $this->assertInstanceOf(Plan::class, $plan);
        $this->assertTrue($plan->isEnable());

        // Delete
        $sdk->plan->remove(self::SERVICE_ID, $plan->getId());
    }
}

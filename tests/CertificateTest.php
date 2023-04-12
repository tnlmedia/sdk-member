<?php

namespace TNLMedia\MemberSDK\Tests;

use ArrayIterator;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use TNLMedia\MemberSDK\Constants\CertificateStatusConstants;
use TNLMedia\MemberSDK\Exceptions\ProtectedException;
use TNLMedia\MemberSDK\MemberSDK;
use TNLMedia\MemberSDK\Nodes\AccessToken;
use TNLMedia\MemberSDK\Nodes\Certificate;
use TNLMedia\MemberSDK\Nodes\User;

class CertificateTest extends TestCase
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
     * Search certificate
     *
     * @depends testSdk
     * @param MemberSDK $sdk
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
     */
    public function testSearch(MemberSDK $sdk)
    {
        $result = $sdk->certificate->search([], null, 0, 1);
        $this->assertInstanceOf(ArrayIterator::class, $result->getList());
        foreach ($result->getList() as $certificate) {
            $this->assertInstanceOf(Certificate::class, $certificate);
            $this->assertNotEmpty($certificate->getSlug());
            $this->assertNotEmpty($certificate->getName());
        }
        $this->assertEquals(1, $result->getCount());
        $this->assertGreaterThan(0, $result->getTotal());
    }

    /**
     * New certificate and update it.
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
    public function testNew(MemberSDK $sdk)
    {
        // Create
        $slug = 'test.' . time();
        $certificate = $sdk->certificate->create($slug, 'Test certificate');
        $this->assertInstanceOf(Certificate::class, $certificate);
        $this->assertEquals($slug, $certificate->getSlug());
        $this->assertEquals('TWD', $certificate->getCurrency());
        $this->assertEquals(0, $certificate->getPrice());
        $this->assertFalse($certificate->isVisible());
        $this->assertFalse($certificate->isEnable());

        // Update
        $certificate = $sdk->certificate->update($certificate->getId(), [
            'status' => CertificateStatusConstants::ENABLED,
        ]);
        $this->assertInstanceOf(Certificate::class, $certificate);
        $this->assertTrue($certificate->isEnable());

        return [
            'sdk' => $sdk,
            'certificate' => $certificate,
        ];
    }

    /**
     * Authorize to user
     *
     * @depends testNew
     * @param array $source
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
    public function testAuthorize(array $source)
    {
        /** @var MemberSDK $sdk */
        $sdk = $source['sdk'];
        /** @var Certificate $certificate */
        $certificate = $source['certificate'];

        $user = $sdk->certificate->authorize($_ENV['USER_ID'], $certificate->getId());
        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($user->hasCertificate($certificate->getId()));
        $this->assertTrue($user->hasCertificate($certificate->getSlug()));

        return [
            'sdk' => $sdk,
            'certificate' => $certificate,
        ];
    }

    /**
     * Remove certificate
     *
     * @depends testAuthorize
     * @param array $source
     * @return void
     * @throws ProtectedException
     * @throws \TNLMedia\MemberSDK\Exceptions\AuthorizeException
     * @throws \TNLMedia\MemberSDK\Exceptions\DuplicateException
     * @throws \TNLMedia\MemberSDK\Exceptions\Exception
     * @throws \TNLMedia\MemberSDK\Exceptions\FormatException
     * @throws \TNLMedia\MemberSDK\Exceptions\NotFoundException
     * @throws \TNLMedia\MemberSDK\Exceptions\RequestException
     * @throws \TNLMedia\MemberSDK\Exceptions\RequireException
     * @throws \TNLMedia\MemberSDK\Exceptions\UnnecessaryException
     * @throws \TNLMedia\MemberSDK\Exceptions\UploadException
     */
    public function testRemove(array $source)
    {
        /** @var MemberSDK $sdk */
        $sdk = $source['sdk'];
        /** @var Certificate $certificate */
        $certificate = $source['certificate'];

        $this->expectException(ProtectedException::class);
        $sdk->certificate->remove($certificate->getId());
    }
}

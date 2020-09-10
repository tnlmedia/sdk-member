<?php

namespace Tnlmedia\MemberSDK\Tests;

use PHPUnit\Framework\TestCase;
use Tnlmedia\MemberSDK\MemberSDK;

class MemberSDKTest extends TestCase 
{
    public $config;
    public $membersdk;

    public function setUp():void 
    {
          @session_start();
          parent::setUp();
          $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
          $this->config = $dotenv->load();
          $this->membersdk = new MemberSDK($this->config);
    }
    

    public function testGetUserById()
    {
        $user = $this->membersdk->getUserById(6);
        $this->assertArrayHasKey('nickname', $user);
    }
    
    public function testRedirect()
    {
        $url = $this->membersdk->getAuthUrl();
        $this->assertEquals(1, $url);
    }

}

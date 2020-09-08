<?php

namespace Tnlmedia\MemberSDK\Tests;

use PHPUnit\Framework\TestCase;
use Tnlmedia\MemberSDK\Member;

class MemberSDKTest extends TestCase 
{
    public $config;
    public $member;

    public function setUp():void 
    {
          @session_start();
          parent::setUp();
          $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
          $this->config = $dotenv->load();
          $this->member = new Member($this->config);
    }
    

    public function testGetUserById()
    {
        $user = $this->member->getUserById(6);
        $this->assertArrayHasKey('nickname', $user);
    }
    
    public function testRedirect()
    {
        $url = $this->member->getAuthUrl();
        $this->assertEquals(1, $url);
    }

}

<?php

namespace Tnlmedia\MemberSDK\Tests;

use PHPUnit\Framework\TestCase;
use Tnlmedia\MemberSDK\Member;

class MemberSDKTest extends TestCase 
{
    public function setUp():void 
    {
          @session_start();
          parent::setUp();
    }
    public function testRedirect()
    {
        $member = new Member($config);
        $member->redirect();
        
    }

    public function testGetUserById()
    {
        $config = [
        ];
        $member = new Member($config);
        $user = $member->getUserById(6);
        $this->assertArrayHasKey('nickname', $user);
    }

}

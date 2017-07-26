<?php

use PHPUnit\Framework\TestCase;

/**
 * @covers OauthClient
 */
final class OauthClientTest extends TestCase
{
    public function setUp()
    {

    }

    public function testConnect()
    {
        $oauth = new OauthClient();
        $this->assertEquals( 16, 12);
    }
}

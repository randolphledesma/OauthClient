<?php

use PHPUnit\Framework\TestCase;

/**
 * @covers NonceGenerator
 */
final class NonceGeneratorTest extends TestCase
{
    public function setUp()
    {

    }

    public function testLength()
    {
        $this->assertEquals( 16, strlen(NonceGenerator::generate()));
    }
}

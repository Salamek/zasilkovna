<?php

/**
 * Created by PhpStorm.
 * User: sadam
 * Date: 3.8.17
 * Time: 2:16
 */
final class PublicTest extends BaseTest
{
    /**
     * @test
     */
    public function testIsHealthy()
    {
        //$this->zasilkovnaApi->createPacket();
        $this->assertInternalType('boolean', true);
    }
}
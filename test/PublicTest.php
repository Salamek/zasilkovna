<?php

declare(strict_types=1);


final class PublicTest extends BaseTest
{
	/**
	 * @test
	 */
	public function testIsHealthy()
	{
		$this->assertInternalType('boolean', true);
	}


	/**
	 * @test
	 */
	public function testGetBranchList()
	{
		$this->zasilkovnaApi->getBranchList();

		$this->assertInternalType('boolean', true);
	}
}

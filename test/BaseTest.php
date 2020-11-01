<?php

declare(strict_types=1);


use Salamek\Zasilkovna\IApi;

abstract class BaseTest extends PHPUnit_Framework_TestCase
{
	protected ?IApi $zasilkovnaApi = null;


	public function setUp(): void
	{
		$configPath = __DIR__ . '/config.json';
		if (file_exists($configPath)) {
			$config = json_decode(file_get_contents($configPath));
			$this->zasilkovnaApi = new \Salamek\Zasilkovna\ApiRest(
				$config->apiPassword
			);
		} else {
			throw new \RuntimeException('config.json not found');
		}
	}
}

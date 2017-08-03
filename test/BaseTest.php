<?php

use Salamek\Zasilkovna\Api;

/**
 * Copyright (C) 2016 Adam Schubert <adam.schubert@sg1-game.net>.
 */
abstract class BaseTest extends PHPUnit_Framework_TestCase
{
    /** @var null|Api */
    protected $zasilkovnaApi = null;

    public function setUp()
    {
        $configPath = __DIR__ . '/config.json';
        if (file_exists($configPath)) {
            $config = json_decode(file_get_contents($configPath));
            $this->zasilkovnaApi = new Api($config->apiPassword);
        }
        else
        {
            throw new \Exception('config.json not found');
        }
    }
}
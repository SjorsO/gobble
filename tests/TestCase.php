<?php

namespace SjorsO\Gobble\Tests;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase as BaseTestCase;
use SjorsO\Gobble\Providers\GobbleProvider;

abstract class TestCase extends BaseTestCase
{
    public $testFilePath;

    public $app;

    public function setUp()
    {
        parent::setUp();

        $this->app = Container::getInstance();

        $provider = new GobbleProvider($this->app);

        $provider->register();

        Facade::clearResolvedInstances();

        Facade::setFacadeApplication($this->app);

        $this->testFilePath = __DIR__.'/Files/';
    }

    protected function tearDown()
    {
        $this->app->flush();

        $this->app = null;
    }
}

<?php

namespace SjorsO\Gobble\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public $testFilePath;

    public function setUp()
    {
        parent::setUp();

        $this->testFilePath = __DIR__.'/Files/';
    }
}

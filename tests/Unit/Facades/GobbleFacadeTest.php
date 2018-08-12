<?php

namespace SjorsO\Gobble\Tests\Unit\Facades;

use Illuminate\Container\Container;
use SjorsO\Gobble\Facades\Gobble;
use SjorsO\Gobble\GuzzleFakeWrapper;
use SjorsO\Gobble\GuzzleWrapper;
use SjorsO\Gobble\Tests\TestCase;

class GobbleFacadeTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        // reset Gobble to its original state, otherwise these tests
        // fail if you change their order.
        Gobble::unfake();

        Gobble::setFacadeApplication(
            Container::getInstance()
        );
    }

    /** @test */
    function it_resolves_to_a_guzzle_wrapper()
    {
        $facadeRoot = Gobble::getFacadeRoot();

        $this->assertInstanceOf(GuzzleWrapper::class, $facadeRoot);
    }

    /** @test */
    function when_faked_it_resolves_to_a_guzzle_fake_wrapper()
    {
        $guzzleFakeWrapper = Gobble::fake();

        $this->assertInstanceOf(GuzzleFakeWrapper::class, $guzzleFakeWrapper);

        $facadeRoot = Gobble::getFacadeRoot();

        $this->assertInstanceOf(GuzzleFakeWrapper::class, $facadeRoot);
    }

    /** @test */
    function it_can_be_unfaked()
    {
        $this->assertInstanceOf(GuzzleWrapper::class, Gobble::getFacadeRoot());

        Gobble::fake();

        $this->assertInstanceOf(GuzzleFakeWrapper::class, Gobble::getFacadeRoot());

        Gobble::unfake();

        $this->assertInstanceOf(GuzzleWrapper::class, Gobble::getFacadeRoot());
    }
}

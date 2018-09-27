<?php

namespace SjorsO\Gobble\Tests\Unit\Facades;

use PHPUnit\Framework\ExpectationFailedException;
use SjorsO\Gobble\Facades\Gobble;
use SjorsO\Gobble\GuzzleFakeWrapper;
use SjorsO\Gobble\GuzzleWrapper;
use SjorsO\Gobble\Tests\TestCase;

class GobbleFacadeTest extends TestCase
{
    /** @test */
    function it_resolves_to_a_guzzle_wrapper()
    {
        $facadeRoot = Gobble::getFacadeRoot();

        $this->assertInstanceOf(GuzzleWrapper::class, $facadeRoot);

        $this->assertFalse(Gobble::isFaked());
    }

    /** @test */
    function when_faked_it_resolves_to_a_guzzle_fake_wrapper()
    {
        $guzzleFakeWrapper = Gobble::fake();

        $this->assertInstanceOf(GuzzleFakeWrapper::class, $guzzleFakeWrapper);

        $facadeRoot = Gobble::getFacadeRoot();

        $this->assertInstanceOf(GuzzleFakeWrapper::class, $facadeRoot);

        $this->assertTrue(Gobble::isFaked());
    }

    /** @test */
    function it_can_be_unfaked()
    {
        $this->assertInstanceOf(GuzzleWrapper::class, Gobble::getFacadeRoot());
        $this->assertFalse(Gobble::isFaked());

        $fakeReturnedClass = Gobble::fake();

        $this->assertInstanceOf(GuzzleFakeWrapper::class, $fakeReturnedClass);
        $this->assertInstanceOf(GuzzleFakeWrapper::class, Gobble::getFacadeRoot());
        $this->assertTrue(Gobble::isFaked());

        $unfakeReturnedClass = Gobble::unfake();

        $this->assertInstanceOf(GuzzleWrapper::class, $unfakeReturnedClass);
        $this->assertInstanceOf(GuzzleWrapper::class, Gobble::getFacadeRoot());
        $this->assertFalse(Gobble::isFaked());
    }

    /** @test */
    function it_can_assert_the_amount_of_responses_in_the_mock_queue()
    {
        Gobble::fake()
            ->assertMockQueueEmpty()
            ->assertMockQueueCount(0)
            ->pushEmptyResponse()
            ->assertMockQueueCount(1);

        try {
            Gobble::assertMockQueueEmpty();
        } catch (ExpectationFailedException $exception) {
            $this->assertStringStartsWith(
                'The Guzzle mock queue did not contain the expected amount of responses (expected: 0, actual 1)',
                $exception->getMessage()
            );
        }
    }
}

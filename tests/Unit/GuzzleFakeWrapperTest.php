<?php

namespace SjorsO\Gobble\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use OutOfBoundsException;
use SjorsO\Gobble\GuzzleFakeWrapper;
use SjorsO\Gobble\Tests\TestCase;

class GuzzleFakeWrapperTest extends TestCase
{
    /** @var GuzzleFakeWrapper $guzzleFakeWrapper */
    protected $guzzleFakeWrapper;

    public function setUp()
    {
        parent::setUp();

        $this->guzzleFakeWrapper = new GuzzleFakeWrapper();
    }

    /** @test */
    function it_throws_an_exception_if_no_responses_are_queued()
    {
        $this->expectException(OutOfBoundsException::class);

        $this->guzzleFakeWrapper->get('https://laravel.com');
    }

    /** @test */
    function it_can_push_a_response()
    {
        $response = new Response(204, [], 'Body content!');

        $this->guzzleFakeWrapper->pushResponse($response);

        /** @var Response $response */
        $response = $this->guzzleFakeWrapper->get('https://laravel.com');

        $this->assertInstanceOf(Response::class, $response);

        $this->assertSame(204, $response->getStatusCode());

        $this->assertSame('Body content!', $response->getBody()->getContents());
    }

    /** @test */
    function it_can_push_string_responses()
    {
        $this->guzzleFakeWrapper->pushString('faked!');

        /** @var Response $response */
        $response = $this->guzzleFakeWrapper->get('https://laravel.com');

        $this->assertInstanceOf(Response::class, $response);

        $this->assertSame(200, $response->getStatusCode());

        $this->assertSame('faked!', $response->getBody()->getContents());
    }

    /** @test */
    function it_can_push_file_responses()
    {
        $filePath = $this->testFilePath.'test-01.json';

        $this->guzzleFakeWrapper->pushFile($filePath);

        /** @var Response $response */
        $response = $this->guzzleFakeWrapper->get('https://laravel.com');

        $this->assertInstanceOf(Response::class, $response);

        $this->assertSame(200, $response->getStatusCode());

        $this->assertSame(
            file_get_contents($filePath),
            $response->getBody()->getContents()
        );
    }
}

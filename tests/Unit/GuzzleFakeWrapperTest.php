<?php

namespace SjorsO\Gobble\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use OutOfBoundsException;
use SjorsO\Gobble\GuzzleFakeWrapper;
use SjorsO\Gobble\Tests\TestCase;

class GuzzleFakeWrapperTest extends TestCase
{
    /** @test */
    function it_throws_an_exception_if_no_responses_are_queued()
    {
        $gobbleFake = new GuzzleFakeWrapper();

        $this->expectException(OutOfBoundsException::class);

        $gobbleFake->get('https://laravel.com');
    }

    /** @test */
    function it_can_push_a_response()
    {
        $gobbleFake = new GuzzleFakeWrapper();

        $response = new Response(204, [], 'Body content!');

        $gobbleFake->pushResponse($response);

        /** @var Response $response */
        $response = $gobbleFake->get('https://laravel.com');

        $this->assertInstanceOf(Response::class, $response);

        $this->assertSame(204, $response->getStatusCode());

        $this->assertSame('Body content!', $response->getBody()->getContents());
    }

    /** @test */
    function it_can_push_an_empty_response()
    {
        $gobbleFake = new GuzzleFakeWrapper();

        $gobbleFake->pushEmptyResponse();

        /** @var Response $response */
        $response = $gobbleFake->get('https://laravel.com');

        $this->assertInstanceOf(Response::class, $response);

        $this->assertSame(200, $response->getStatusCode());

        $this->assertSame('', $response->getBody()->getContents());
    }

    /** @test */
    function it_can_push_string_responses()
    {
        $gobbleFake = new GuzzleFakeWrapper();

        $gobbleFake->pushString('faked!');

        /** @var Response $response */
        $response = $gobbleFake->get('https://laravel.com');

        $this->assertInstanceOf(Response::class, $response);

        $this->assertSame(200, $response->getStatusCode());

        $this->assertSame('faked!', $response->getBody()->getContents());
    }

    /** @test */
    function it_can_push_file_responses()
    {
        $gobbleFake = new GuzzleFakeWrapper();

        $filePath = $this->testFilePath.'test-01.json';

        $gobbleFake->pushFile($filePath);

        /** @var Response $response */
        $response = $gobbleFake->get('https://laravel.com');

        $this->assertInstanceOf(Response::class, $response);

        $this->assertSame(200, $response->getStatusCode());

        $this->assertSame(
            file_get_contents($filePath),
            $response->getBody()->getContents()
        );
    }
}

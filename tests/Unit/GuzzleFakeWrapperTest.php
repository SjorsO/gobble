<?php

namespace SjorsO\Gobble\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use OutOfBoundsException;
use RuntimeException;
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

        $this->assertEmptyResponse($response);
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
    function it_can_push_json_responses()
    {
        $gobbleFake = new GuzzleFakeWrapper();

        $gobbleFake->pushJson([
            'fact' => 'Cats are great!',
        ]);

        /** @var Response $response */
        $response = $gobbleFake->get('https://laravel.com');

        $this->assertInstanceOf(Response::class, $response);

        $this->assertSame(200, $response->getStatusCode());

        $this->assertSame('{"fact":"Cats are great!"}', $response->getBody()->getContents());
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

    /** @test */
    function push_response_methods_are_fluent()
    {
        (new GuzzleFakeWrapper)
            ->pushEmptyResponse()
            ->pushString('OK')
            ->pushFile($this->testFilePath.'test-01.json')
            ->pushResponse(new Response)
            ->pushEmptyResponse();

        $this->assertTrue(true);
    }

    /** @test */
    function it_keeps_a_history_of_requests_made()
    {
        $gobbleFake = (new GuzzleFakeWrapper)
            ->pushEmptyResponse()
            ->pushEmptyResponse();

        $response1 = $gobbleFake->get('https://laravel.com');

        $response2 = $gobbleFake->get('https://golang.org');

        $history = $gobbleFake->requestHistory();

        $this->assertCount(2, $history);

        $this->assertSame(
            'https://laravel.com',
            (string) $history[0]->request->getUri()
        );

        $this->assertSame($response1, $history[0]->response);

        $this->assertSame(
            'https://golang.org',
            (string) $history[1]->request->getUri()
        );

        $this->assertSame($response2, $history[1]->response);
    }

    /** @test */
    function it_can_get_the_last_request_made()
    {
        $gobbleFake = (new GuzzleFakeWrapper)
            ->pushEmptyResponse()
            ->pushEmptyResponse();

        $gobbleFake->get('https://laravel.com');

        $lastResponse = $gobbleFake->get('https://golang.org');

        $this->assertSame($lastResponse, $gobbleFake->lastRequest()->response);
    }

    /** @test */
    function it_throws_an_exception_when_trying_to_get_the_last_request_when_no_requests_are_made()
    {
        $this->expectException(RuntimeException::class);

        $gobbleFake = new GuzzleFakeWrapper();

        $gobbleFake->lastRequest();
    }

    /** @test */
    function it_can_autofill_the_response_stack()
    {
        $gobbleFake = new GuzzleFakeWrapper();

        $gobbleFake->autofillResponseStack();

        $this->assertEmptyResponse(
            $gobbleFake->get('https://laravel.com')
        );

        $this->assertEmptyResponse(
            $gobbleFake->get('https://golang.org')
        );

        $gobbleFake->assertMockQueueEmpty();
    }

    /** @test */
    function it_only_autofills_when_the_stack_is_empty()
    {
        $gobbleFake = New GuzzleFakeWrapper();

        $gobbleFake->autofillResponseStack();

        $gobbleFake->pushString('This is a pushed response');

        $this->assertSame(
            'This is a pushed response',
            $gobbleFake->get('https://laravel.com')->getBody()->getContents()
        );

        $this->assertEmptyResponse(
            $gobbleFake->get('https://golang.org')
        );

        $gobbleFake->assertMockQueueEmpty();
    }

    private function assertEmptyResponse($response)
    {
        $this->assertInstanceOf(Response::class, $response);

        $this->assertSame(200, $response->getStatusCode());

        $this->assertSame('', $response->getBody()->getContents());
    }
}

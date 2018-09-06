<?php

namespace SjorsO\Gobble\Tests\Unit\Support;

use SjorsO\Gobble\Facades\Gobble;
use SjorsO\Gobble\GuzzleFakeWrapper;
use SjorsO\Gobble\Support\RequestHistory;
use SjorsO\Gobble\Tests\TestCase;

class RequestHistoryTest extends TestCase
{
    /** @test */
    function it_can_be_accessed_like_the_normal_guzzle_return_array()
    {
        $guzzleFake = (new GuzzleFakeWrapper)->pushEmptyResponse();

        $guzzleFake->get('https://laravel.com');

        $history = $guzzleFake->lastRequest();

        $this->assertInstanceOf(RequestHistory::class, $history);

        $this->assertNotNull($history->request);
        $this->assertNotNull($history['request']);

        $this->assertSame($history->request,  $history['request']);
        $this->assertSame($history->response, $history['response']);
        $this->assertSame($history->error,    $history['error']);
        $this->assertSame($history->options,  $history['options']);
    }

    /** @test */
    function it_can_assert_the_request_uri()
    {
        Gobble::fake()->pushEmptyResponse();

        Gobble::get('https://laravel.com');

        Gobble::lastRequest()->assertRequestUri('https://laravel.com');
    }

    /** @test */
    function it_can_assert_the_exact_request_body()
    {
        Gobble::fake()->pushEmptyResponse();

        Gobble::post('https://laravel.com', ['body' => 'Foo']);

        Gobble::lastRequest()->assertRequestBodyExact('Foo');
    }

    /** @test */
    function it_can_assert_the_request_body_contains_a_subset_of_json()
    {
        Gobble::fake()->pushEmptyResponse();

        Gobble::post('https://laravel.com', [
            'json' => [
                'deviceIds' => ['aa1', 'aa2'],
                'magicWord' => 'Please',
            ],
        ]);

        Gobble::lastRequest()->assertRequestBodyJson([
            'deviceIds' => ['aa1'],
            'magicWord' => 'Please',
        ]);
    }

    /** @test */
    function it_can_assert_the_exact_request_json_body()
    {
        Gobble::fake()->pushEmptyResponse();

        Gobble::post('https://laravel.com', [
            'json' => [
                'deviceIds' => ['aa1', 'aa2'],
                'magicWord' => 'Please',
            ],
        ]);

        Gobble::lastRequest()->assertRequestBodyExactJson([
            'magicWord' => 'Please',
            'deviceIds' => ['aa1', 'aa2'],
        ]);
    }
}

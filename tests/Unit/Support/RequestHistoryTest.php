<?php

namespace SjorsO\Gobble\Tests\Unit\Support;

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
}

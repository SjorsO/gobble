<?php

namespace SjorsO\Gobble\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use SjorsO\Gobble\GuzzleWrapper;
use SjorsO\Gobble\Tests\TestCase;

class GuzzleWrapperTest extends TestCase
{
    /** @test */
    function it_proxies_calls_to_guzzle()
    {
        /** @var Response $response */
        $response = (new GuzzleWrapper)->get('https://laravel.com');

        $this->assertInstanceOf(Response::class, $response);

        $this->assertContains('artisan', $response->getBody()->getContents());
    }
}

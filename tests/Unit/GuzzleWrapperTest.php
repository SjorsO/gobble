<?php

namespace SjorsO\Gobble\Tests\Unit;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Psr7\Response;
use SjorsO\Gobble\GuzzleWrapper;
use SjorsO\Gobble\Tests\TestCase;

class GuzzleWrapperTest extends TestCase
{
    /** @var \GuzzleHttp\Client $guzzleWrapper */
    protected $guzzleWrapper;

    public function setUp()
    {
        parent::setUp();

        $this->guzzleWrapper = new GuzzleWrapper(
            new Guzzle()
        );
    }

    /** @test */
    function it_proxies_calls_to_guzzle()
    {
        /** @var Response $response */
        $response = $this->guzzleWrapper->get('https://laravel.com');

        $this->assertInstanceOf(Response::class, $response);

        $this->assertContains('artisan', $response->getBody()->getContents());
    }
}

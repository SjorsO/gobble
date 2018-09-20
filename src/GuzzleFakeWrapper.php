<?php

namespace SjorsO\Gobble;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Assert as PHPUnit;
use RuntimeException;
use SjorsO\Gobble\Support\RequestHistory;

class GuzzleFakeWrapper
{
    protected $guzzle;

    /** @var MockHandler */
    protected $mockHandler;

    protected $requestHistory = [];

    public function __construct()
    {
        $handler = HandlerStack::create(
            $this->mockHandler = new MockHandler()
        );

        $handler->push(
            Middleware::history($this->requestHistory)
        );

        $this->guzzle = new Guzzle(['handler' => $handler]);
    }

    public function pushResponse($response)
    {
        $this->mockHandler->append($response);

        return $this;
    }

    public function pushEmptyResponse($status = 200, $headers = [])
    {
        return $this->pushResponse(
            new Response($status, $headers)
        );
    }

    public function pushString($string, $status = 200, $headers = [])
    {
        return $this->pushResponse(
            new Response($status, $headers, $string)
        );
    }

    public function pushJson(array $data, $status = 200, $headers = [])
    {
        return $this->pushResponse(
            new Response($status, $headers, json_encode($data))
        );
    }

    public function pushFile($filePath, $status = 200, $headers = [])
    {
        $string = file_get_contents($filePath);

        return $this->pushResponse(
            new Response($status, $headers, $string)
        );
    }

    /**
     * @return array|RequestHistory[]
     */
    public function requestHistory()
    {
        return array_map(function ($guzzleRequestHistory) {
            return new RequestHistory($guzzleRequestHistory);
        }, $this->requestHistory);
    }

    public function lastRequest()
    {
        $history = static::requestHistory();

        if (count($history) === 0) {
            throw new RuntimeException('History does not contain any requests');
        }

        return end($history);
    }

    public function assertMockQueueCount($expected)
    {
        PHPUnit::assertSame(
            $expected,
            $actual = $this->mockHandler->count(),
            "The Guzzle mock queue did not contain the expected amount of responses (expected: $expected, actual $actual)"
        );

        return $this;
    }

    public function assertMockQueueEmpty()
    {
        return $this->assertMockQueueCount(0);
    }

    public function __call($method, $arguments)
    {
        return $this->guzzle->{$method}(...$arguments);
    }
}

<?php

namespace SjorsO\Gobble;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
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

    public function __call($method, $arguments)
    {
        return $this->guzzle->{$method}(...$arguments);
    }
}

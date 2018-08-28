<?php

namespace SjorsO\Gobble;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class GuzzleFakeWrapper
{
    protected $guzzle;

    /** @var $mockHandler MockHandler */
    protected $mockHandler;

    public function __construct()
    {
        $handler = HandlerStack::create(
            $this->mockHandler = new MockHandler()
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

    public function __call($method, $arguments)
    {
        return $this->guzzle->{$method}(...$arguments);
    }
}

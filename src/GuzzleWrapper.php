<?php

namespace SjorsO\Gobble;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Container\Container;

class GuzzleWrapper
{
    public function __call($method, $arguments)
    {
        $guzzle = Container::getInstance()->make(GuzzleClient::class);

        return $guzzle->{$method}(...$arguments);
    }
}

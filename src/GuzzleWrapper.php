<?php

namespace SjorsO\Gobble;

use GuzzleHttp\Client;
use Illuminate\Container\Container;

class GuzzleWrapper
{
    public function __call($method, $arguments)
    {
        $guzzle = Container::getInstance()->make(Client::class);

        return $guzzle->{$method}(...$arguments);
    }
}

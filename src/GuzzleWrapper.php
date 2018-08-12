<?php

namespace SjorsO\Gobble;

use GuzzleHttp\Client as Guzzle;

class GuzzleWrapper
{
    protected $guzzle;

    public function __construct(Guzzle $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    public function __call($method, $arguments)
    {
        return $this->guzzle->{$method}(...$arguments);
    }
}

<?php

namespace SjorsO\Gobble\Support;

use ArrayAccess;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use RuntimeException;

class RequestHistory implements ArrayAccess
{
    /** @var Request */
    public $request;

    /** @var Response */
    public $response;

    public $error;

    public $options;

    public function __construct(array $guzzleHistory)
    {
        $this->request = $guzzleHistory['request'];

        $this->response = $guzzleHistory['response'];

        $this->error = $guzzleHistory['error'];

        $this->options = $guzzleHistory['options'];
    }

    public function offsetExists($offset)
    {
        return in_array($offset, [
            'request',
            'response',
            'error',
            'options',
        ]);
    }

    public function offsetGet($offset)
    {
        if (! $this->offsetExists($offset)) {
            throw new RuntimeException();
        }

        return $this->{$offset};
    }

    public function offsetSet($offset, $value)
    {
        throw new RuntimeException();
    }

    public function offsetUnset($offset)
    {
        throw new RuntimeException();
    }
}

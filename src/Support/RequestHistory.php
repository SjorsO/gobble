<?php

namespace SjorsO\Gobble\Support;

use ArrayAccess;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Arr;
use PHPUnit\Framework\Assert as PHPUnit;
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

    public function requestBody()
    {
        return $this->request->getBody()->getContents();
    }

    public function decodeRequestJson($key = null)
    {
        $decodedResponse = json_decode($this->requestBody(), true);

        if (is_null($decodedResponse) || $decodedResponse === false) {
            PHPUnit::fail('Request contains invalid JSON.');
        }

        return data_get($decodedResponse, $key);
    }

    public function assertRequestBodyExact($expected)
    {
        PHPUnit::assertSame($expected, $this->requestBody());

        return $this;
    }

    public function assertRequestBodyJson(array $data, $strict = false)
    {
        PHPUnit::assertArraySubset($data, $this->decodeRequestJson(), $strict);

        return $this;
    }

    public function assertRequestBodyExactJson(array $expected)
    {
        $expected = json_encode(Arr::sortRecursive($expected));

        $actual = json_encode(Arr::sortRecursive(
            (array) $this->decodeRequestJson()
        ));

        PHPUnit::assertSame($expected, $actual);

        return $this;
    }

    public function assertRequestUri($expected)
    {
        PHPUnit::assertSame($expected, (string) $this->request->getUri());

        return $this;
    }

    public function offsetExists($offset)
    {
        return in_array($offset, ['request', 'response', 'error', 'options']);
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

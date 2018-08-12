<?php

namespace SjorsO\Gobble\Facades;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Support\Facades\Facade;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use SjorsO\Gobble\GuzzleFakeWrapper;
use SjorsO\Gobble\GuzzleWrapper;

/**
 * @method static ResponseInterface request(string|UriInterface $uri, array $options = [])
 *
 * @method static ResponseInterface get(string|UriInterface $uri, array $options = [])
 * @method static ResponseInterface head(string|UriInterface $uri, array $options = [])
 * @method static ResponseInterface put(string|UriInterface $uri, array $options = [])
 * @method static ResponseInterface post(string|UriInterface $uri, array $options = [])
 * @method static ResponseInterface patch(string|UriInterface $uri, array $options = [])
 * @method static ResponseInterface delete(string|UriInterface $uri, array $options = [])
 * @method static PromiseInterface getAsync(string|UriInterface $uri, array $options = [])
 * @method static PromiseInterface headAsync(string|UriInterface $uri, array $options = [])
 * @method static PromiseInterface putAsync(string|UriInterface $uri, array $options = [])
 * @method static PromiseInterface postAsync(string|UriInterface $uri, array $options = [])
 * @method static PromiseInterface patchAsync(string|UriInterface $uri, array $options = [])
 * @method static PromiseInterface deleteAsync(string|UriInterface $uri, array $options = [])
 *
 * @see \GuzzleHttp\Client
 */
class Gobble extends Facade
{
    protected static $faked = false;

    protected static function getFacadeAccessor()
    {
        return static::$faked
            ? GuzzleFakeWrapper::class
            : GuzzleWrapper::class;
    }

    protected static function fake()
    {
        self::$faked = true;
    }
}

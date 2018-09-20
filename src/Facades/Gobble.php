<?php

namespace SjorsO\Gobble\Facades;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Support\Facades\Facade;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use SjorsO\Gobble\GuzzleFakeWrapper;
use SjorsO\Gobble\GuzzleWrapper;
use SjorsO\Gobble\Support\RequestHistory;

/**
 * @method static ResponseInterface request(string $method, string|UriInterface $uri, array $options = [])
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
 * @method static GuzzleFakeWrapper pushResponse($response)
 * @method static GuzzleFakeWrapper pushEmptyResponse($status = 200, $headers = [])
 * @method static GuzzleFakeWrapper pushString($string, $status = 200, $headers = [])
 * @method static GuzzleFakeWrapper pushFile($filePath, $status = 200, $headers = [])
 *
 * @method static GuzzleFakeWrapper assertMockQueueCount($expected)
 * @method static GuzzleFakeWrapper assertMockQueueEmpty()
 *
 * @method static array|RequestHistory[] requestHistory()
 * @method static array|RequestHistory lastRequest()
 *
 * @see \GuzzleHttp\Client
 */
class Gobble extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Gobble';
    }

    /**
     * @return GuzzleFakeWrapper
     */
    public static function fake()
    {
        static::swap(
            static::$app[GuzzleFakeWrapper::class]
        );

        return static::getFacadeRoot();
    }

    /**
     * @return GuzzleWrapper
     */
    public static function unfake()
    {
        static::swap(
            static::$app[GuzzleWrapper::class]
        );

        return static::getFacadeRoot();
    }
}

# Gobble
A Laravel specific wrapper around Guzzle that makes it easy to mock responses.

## Install
```bash
composer require sjorso/gobble
```

## Usage
You can use the `Gobble` facade in your code to make requests with Guzzle. The Gobble facade proxies all method calls to `GuzzleHttp\Client`:
```php
use SjorsO\Gobble\Facades\Gobble;

$response = Gobble::get('https://laravel.com');
```

### Mocking responses
When writing tests, you can fake Gobble to make it use [Guzzle's built-in mock handler](http://docs.guzzlephp.org/en/stable/testing.html). When `Gobble` is faked you can use it to push responses to the mock handler stack:
```php
/** @test */
function it_can_get_a_cat_fact()
{
    Gobble::fake()->pushJson(['fact' => 'Cats are great!']);

    // This job makes a call using Gobble to "https://catfact.ninja/fact"
    CreateCatFactJob::dispatchNow();

    $this->assertSame('Cats are great!', CatFact::findOrFail(1)->fact);
}
```

When Gobble is faked, you can use the following methods to push fake responses to the mock handler stack:
```php
Gobble::pushResponse($response);

Gobble::pushEmptyResponse($status = 200, $headers = []);

Gobble::pushString($string, $status = 200, $headers = []);

Gobble::pushJson(array $data, $status = 200, $headers = []);

Gobble::pushFile($filePath, $status = 200, $headers = []);
```

You can also make Gobble automatically push an empty response to the response stack when a request is made. This is useful in some situations, and prevents your log from filling up with "Mock queue is empty" errors:
```php
Gobble::fake()->autofillResponseStack();
```

Gobble offers two methods to assert the amount of responses in the mock handler queue:
```php
Gobble::assertMockQueueCount(3);

Gobble::assertMockQueueEmpty();
```

### Request history
When Gobble is faked, it uses [Guzzle's built-in history middleware](http://docs.guzzlephp.org/en/stable/testing.html#history-middleware) to keep track of all requests made. Request history entries are wrapped in a `RequestHistory` class to add a handful of useful assertions, and to improve IDE auto-completion.
```php
/** @test */
function it_makes_a_call_to_the_cat_fact_api()
{
    Gobble::fake()->pushEmptyResponse();

    CreateCatFactJob::dispatchNow();

    $history = Gobble::requestHistory();

    $this->assertCount(1, $history);

    $history[0]->assertRequestUri('https://catfact.ninja/fact');
}
```

You can use the `lastRequest()` method to get the last request made with Gobble:
```php
/** @test */
function it_is_an_example_in_the_readme()
{
    Gobble::fake()->pushEmptyResponse();

    $response = Gobble::get('https://example.com');

    $lastRequest = Gobble::lastRequest();

    // $response === $lastRequest->response
}
```

The `RequestHistory` class offers the following assertions:
```php
public function assertRequestBodyExact($expected);

public function assertRequestBodyJson(array $data, $strict = false);

public function assertRequestBodyExactJson(array $data);

public function assertRequestBodyContains($string);

public function assertRequestBodyDoesntContain($string);

public function assertRequestUri($expected);

public function assertRequestHeaderPresent($key);

public function assertRequestHeaderMissing($key);

public function assertRequestHeader($key, $expected);
```

### Guzzle configuration
For every call made the `GuzzleWrapper` resolves a `GuzzleHttp\Client` from the container. If `GuzzleHttp\Client` is not bound in the container a new Guzzle client is created. You can bind the Guzzle client in the container to configure it:
```php
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(\GuzzleHttp\Client::class, function () {
            return new \GuzzleHttp\Client(['timeout' => 5, 'connect_timeout' => 5]);
        });
    }

    public function boot()
    {
        //
    }
}
```

## License

This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

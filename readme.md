# Gobble
A Laravel specific wrapper around Guzzle that makes it easy to mock responses.

## Install
```bash
composer require sjorso/gobble
```

## Usage
You can use the `Gobble` facade in your code to make requests with Guzzle. The Gobble facade resolves to a `GuzzleWrapper` instance which proxies all method calls to `GuzzleHttp\Client`:
```php
use SjorsO\Gobble\Facades\Gobble as Guzzle;

$response = Guzzle::get('https://laravel.com');
```

### Mocking responses
When writing tests, you can fake Gobble to make it use [Guzzle's built-in mock handler](http://docs.guzzlephp.org/en/stable/testing.html). When Gobble is faked it returns a `GuzzleFakeWrapper` instance. Using this fake wrapper you can easily push responses to the mock handler stack:
```php
/** @test */
function it_can_get_a_cat_fact()
{
    Gobble::fake()->pushString('{"fact": "Cats are great!"}', 200);

    // This job makes a call using Gobble to "https://catfact.ninja/fact"
    CreateCatFactJob::dispatchNow();

    $this->assertSame('Cats are great!', CatFact::findOrFail(1)->fact);
}
```

When Gobble is faked, you can use the following methods to push fake responses to the mock handler stack:
```php
public function pushResponse($response);

public function pushEmptyResponse($status = 200, $headers = []);

public function pushString($string, $status = 200, $headers = []);

public function pushFile($filePath, $status = 200, $headers = []);
```

### Request history
When Gobble is faked, it uses [Guzzle's built-in history middleware](http://docs.guzzlephp.org/en/stable/testing.html#history-middleware) to keep track of all requests made. You can use this history to make assertions about the calls that are made with Guzzle:
```php
/** @test */
function it_makes_a_call_to_the_cat_fact_api()
{
    Gobble::fake()->pushEmptyResponse();

    CreateCatFactJob::dispatchNow();

    $history = Gobble::requestHistory();

    $this->assertCount(1, $history);

    $this->assertSame(
        'https://catfact.ninja/fact',
        (string) $history[0]->request->getUri()
    );
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

### Guzzle configuration
For every call made the `GuzzleWrapper` resolves a `GuzzleHttp\Client` from the container. If `GuzzleHttp\Client` is not bound in the container a new Guzzle client is created. You can bind the Guzzle client in the container to configure it:
```php
class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->bind(\GuzzleHttp\Client::class, function () {
            return new \GuzzleHttp\Client([
                'timeout' => 5,
                'connect_timeout' => 5,
            ]);
        });
    }
}
```

## License

This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

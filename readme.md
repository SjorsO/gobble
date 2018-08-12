# Readme
Laravel facade for Guzzle that is easy to fake.

## Install
```bash
composer require sjorso/gobble
```

## Usage
You can use the `Gobble` facade in your code to make requests with Guzzle. If Gobble is not being faked, all method calls are proxied to the underlying `Guzzle/Client` class:
```php
$response = Gobble::request('GET', 'https://laravel.com');
``` 

## License

This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

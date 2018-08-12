<?php

namespace SjorsO\Gobble\Providers;

use Illuminate\Support\ServiceProvider;
use SjorsO\Gobble\GuzzleFakeWrapper;
use SjorsO\Gobble\GuzzleWrapper;

class GobbleProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->singleton(GuzzleWrapper::class);

        $this->app->singleton(GuzzleFakeWrapper::class);
    }
}

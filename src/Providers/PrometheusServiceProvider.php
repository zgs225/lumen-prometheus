<?php

namespace Prometheus\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Prometheus\Contracts\Store;
use Prometheus\Stores\LaravelRedis;

class PrometheusServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(Store::class, function(Application $app) {
            return new LaravelRedis($app->make('redis'));
        });
    }

    public function provides()
    {
    }
}
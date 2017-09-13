<?php

namespace Prometheus\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Prometheus\Contracts\Registry;
use Prometheus\Contracts\Store;
use Prometheus\Registry\StoreRegistry;
use Prometheus\Stores\LaravelRedis;
use Prometheus\Supports\Prometheus;

class PrometheusServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(Store::class, function(Application $app) {
            return new LaravelRedis($app->make('redis'));
        });

        $this->app->singleton(Registry::class, function(Application $app) {
            return new StoreRegistry($app, '_default');
        });

        $this->app->singleton(Prometheus::class, function(Application $app) {
            return new Prometheus($app);
        });
    }

    public function provides()
    {
        return [Store::class, Registry::class];
    }
}
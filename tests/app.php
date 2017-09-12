<?php

require_once __DIR__ .'/../vendor/autoload.php';

use Illuminate\Container\Container;
use Prometheus\Contracts\Store;
use Prometheus\Stores\LaravelRedis;

$container = new Container();
Container::setInstance($container);


$container->singleton('redis', function(Container $container) {
    $redis = new Illuminate\Redis\RedisManager('phpredis', [
        'default' => [
            'host'         => 'redis',
            'port'         => 6379,
            'password'     => null,
            'database'     => 0,
            'prefix'       => 'lord_v3',
            'read_timeout' => 2
        ]
    ]);
    return $redis;
});

$container->singleton(Store::class, function(Container $container) {
    return new LaravelRedis($container->make('redis'));
});

return $container;

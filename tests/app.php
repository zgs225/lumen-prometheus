<?php

require_once __DIR__ .'/../vendor/autoload.php';

use Laravel\Lumen\Application;

$app = new Application(realpath(__DIR__.'/../'));
$app->withFacades();

$app->singleton('redis', function() {
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

$app->register(\Prometheus\Providers\PrometheusServiceProvider::class);

return $app;

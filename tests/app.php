<?php

require_once __DIR__ .'/../vendor/autoload.php';

use Illuminate\Container\Container;

$container = new Container();
Container::setInstance($container);

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

$container->instance('redis', $redis);

return $container;

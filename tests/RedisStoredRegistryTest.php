<?php

use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;
use Prometheus\Metrics\Stores\Counter;
use Prometheus\Registry\StoreRegistry;
use Prometheus\Stores\LaravelRedis;

class RedisStoredRegistryTest extends TestCase
{
    public function testRegistry()
    {
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
        $app      = new Container();
        $app->instance('redis', $redis);
        Container::setInstance($app);
        $registry = StoreRegistry::defaultRegistry();
        $store    = new LaravelRedis($redis);
        $counter  = new Counter($store, $redis, 'lord_v3', 'test', 'total', '测试计数器', ['complex' => 4, 'status' => 1]);
        $registry->register($counter);
        $counter  = new Counter($store, $redis, 'lord_v3', 'test', 'total', '测试计数器', ['complex' => 4, 'status' => 2]);
        $registry->register($counter);
        $all      = $registry->all();
        $this->assertEquals(2, count($all));
        $registry->unregister($counter);
        $all      = $registry->all();
        $this->assertEquals(1, count($all));
        $registry->unregister($counter);
    }
}
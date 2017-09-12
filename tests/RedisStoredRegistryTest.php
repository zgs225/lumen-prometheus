<?php

use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;
use Prometheus\Contracts\Store;
use Prometheus\Metrics\Stores\Counter;
use Prometheus\Registry\StoreRegistry;

class RedisStoredRegistryTest extends TestCase
{
    public function testRegistry()
    {
        $registry = StoreRegistry::defaultRegistry();
        $registry->clear();
        $counter  = new Counter(Container::getInstance(), 'lord_v3', 'test', 'total', '测试计数器', ['complex' => 4, 'status' => 1]);
        $registry->register($counter);
        $counter  = new Counter(Container::getInstance(), 'lord_v3', 'test', 'total', '测试计数器', ['complex' => 4, 'status' => 2]);
        $registry->register($counter);
        $all      = $registry->all();
        $this->assertEquals(2, count($all));
        $registry->unregister($counter);
        $all      = $registry->all();
        $this->assertEquals(1, count($all));
        $registry->unregister($counter);
    }
}
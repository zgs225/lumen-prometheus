<?php

use Illuminate\Container\Container;
use Illuminate\Redis\RedisManager;
use PHPUnit\Framework\TestCase;
use Prometheus\Bridges\TextFormatBridge;
use Prometheus\Metrics\Counter;
use Prometheus\Metrics\Stores\Counter as PCounter;
use Prometheus\Registry\BaseRegistry;
use Prometheus\Registry\StoreRegistry;
use Prometheus\Stores\LaravelRedis;

class TextBridgeTest extends TestCase
{
    public function testBaseRegistry()
    {
        $registry = BaseRegistry::defaultRegistry();
        $counter  = new Counter("lord_v3", "test", "total", "测试计数器", []);
        $counter2 = new Counter('lord_v3', 'test', 'total', '测试计数器', ['complex' => 4, 'status' => 1]);
        $registry->register($counter);
        $registry->register($counter2);
        $counter->inc();
        $bridge   = new TextFormatBridge($registry);
        $text     = $bridge->bridge();
        $expected = <<< EOT
# HELP lord_v3_test_total 测试计数器
# TYPE lord_v3_test_total counter
lord_v3_test_total 1
# HELP lord_v3_test_total 测试计数器
# TYPE lord_v3_test_total counter
lord_v3_test_total{complex="4",status="1"} 0

EOT;
        $this->assertEquals($expected, $text);
    }

    public function testRedisStoredRegistry()
    {
        $registry = StoreRegistry::defaultRegistry();
        $registry->clear();
        $counter  = new PCounter(Container::getInstance(), 'lord_v3', 'test', 'total', '测试计数器', []);
        $oldVal1  = $counter->getValue();
        $counter->inc();
        $registry->register($counter);
        $counter  = new PCounter(Container::getInstance(), 'lord_v3', 'test', 'total', '测试计数器', ['complex' => 4, 'status' => 2]);
        $oldVal2  = $counter->getValue();
        $counter->add(1.3);
        $registry->register($counter);
        $bridge   = new TextFormatBridge($registry);
        $text     = $bridge->bridge();
        $expected = <<< EOT
# HELP lord_v3_test_total 测试计数器
# TYPE lord_v3_test_total counter
lord_v3_test_total %d
# HELP lord_v3_test_total 测试计数器
# TYPE lord_v3_test_total counter
lord_v3_test_total{complex="4",status="2"} %s

EOT;
        $expected = sprintf($expected, $oldVal1 + 1, $oldVal2 + 1.3);
        $this->assertEquals($expected, $text);
    }
}
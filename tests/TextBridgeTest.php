<?php

use PHPUnit\Framework\TestCase;
use Prometheus\Bridges\TextFormatBridge;
use Prometheus\Metrics\Counter;
use Prometheus\Registry\BaseRegistry;

class TextBridgeTest extends TestCase
{
    public function testBridge()
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
}
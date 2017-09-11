<?php

class TextBridgeTest extends \PHPUnit\Framework\TestCase
{
    public function testBridge()
    {
        $registry = \Prometheus\Registry\BaseRegistry::defaultRegistry();
        $counter  = new \Prometheus\Metrics\Counter("lord_v3", "test", "total", "测试计数器", []);
        $counter2 = new \Prometheus\Metrics\Counter('lord_v3', 'test', 'total', '测试计数器', ['complex' => 4, 'status' => 1]);
        $registry->register($counter);
        $registry->register($counter2);
        $counter->inc();
        $bridge   = new \Prometheus\Bridges\TextFormatBridge($registry);
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
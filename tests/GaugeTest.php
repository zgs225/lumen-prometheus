<?php

use PHPUnit\Framework\TestCase;

class GaugeTest extends TestCase
{
    public function testGauge()
    {
        $gauge = \Prometheus\Facades\Prometheus::gauge()
            ->_namespace('lord_v3')
            ->subsystem('test')
            ->name('gauge')
            ->help('测试仪表盘')
            ->build();
        $gauge->set(3.14);
        $registry = new \Prometheus\Registry\BaseRegistry('test');
        $bridge   = new \Prometheus\Bridges\TextFormatBridge($registry);
        $expected = <<< EOF
# HELP lord_v3_test_gauge 测试仪表盘
# TYPE lord_v3_test_gauge gauge
lord_v3_test_gauge 3.14

EOF;
        $registry->register($gauge);
        $this->assertEquals($expected, $bridge->bridge());
    }
}
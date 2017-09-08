<?php

use PHPUnit\Framework\TestCase;
use Prometheus\Metrics\Counter;
use Prometheus\Metrics\Type;

class CounterTest extends TestCase
{
    public function testAdd()
    {
        $counter = new Counter("lord_v3", "task", "total", "统计生成任务总数", ['complex' => 4, 'admin_user' => 10069]);
        $this->assertEquals('lord_v3_task_total', $counter->getFQName());
        $this->expectException(\InvalidArgumentException::class);
        $counter->add(-1);
    }

    public function testCollect()
    {
        $counter = new Counter("lord_v3", "task", "total", "统计生成任务总数", ['complex' => 4, 'admin_user' => 10069]);
        $counter->inc();
        $familyL = $counter->collect();
        $this->assertEquals(1, count($familyL));
        /** @var \Prometheus\Metrics\MetricFamilySamples $family */
        $family  = $familyL[0];
        $this->assertEquals(Type::COUNTER, $family->getType());
        $samples = $family->getSamples();
        $this->assertEquals(1, count($samples));
        /** @var \Prometheus\Metrics\Sample $sample */
        $sample  = $samples[0];
        $this->assertEquals(1, $sample->getValue());
    }
}
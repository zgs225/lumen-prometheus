<?php

use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;
use Prometheus\Metrics\Stores\Counter;
use Prometheus\Metrics\Type;

class RedisStoredCounterTest extends TestCase
{
    public function testCounter()
    {
        $counter = new Counter(Container::getInstance(), 'lord_v3', 'test', 'total', '测试计数器', ['complex' => 4, 'status' => 1]);
        $counter->inc();
        $oldVal  = $counter->getValue() ?: 0;
        $counter = new Counter(Container::getInstance(), 'lord_v3', 'test', 'total', '测试计数器', ['complex' => 4, 'status' => 1]);
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
        $this->assertEquals($oldVal+1, $sample->getValue());
    }
}
<?php

use PHPUnit\Framework\TestCase;
use Prometheus\Facades\Prometheus;
use Prometheus\Metrics\Type;

class PrometheusTest extends TestCase
{
    public function testCounter()
    {
        $counter = Prometheus::counter()
            ->_namespace('lord_v3')
            ->subsystem('task')
            ->name('total')
            ->help('统计生成任务总数')
            ->labels(['complex' => 4, 'admin_user' => 10069])
            ->build();
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
        $oldVal  = $sample->getValue();

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
        $newVal  = $sample->getValue();
        $this->assertEquals(1, $newVal-$oldVal);
    }
}
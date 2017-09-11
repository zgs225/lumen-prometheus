<?php

use PHPUnit\Framework\TestCase;
use Prometheus\Metrics\Type;
use Prometheus\Stores\LaravelRedis;

class RedisStoredCounterTest extends TestCase
{
    public function testCounter()
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
        $store   = new LaravelRedis($redis);
        $counter = new \Prometheus\Metrics\Stores\Counter($store, $redis, 'lord_v3', 'test', 'total', '测试计数器', ['complex' => 4, 'status' => 1]);
        $counter->inc();
        $oldVal  = $counter->getValue() ?: 0;
        $counter = new \Prometheus\Metrics\Stores\Counter($store, $redis, 'lord_v3', 'test', 'total', '测试计数器', ['complex' => 4, 'status' => 1]);
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
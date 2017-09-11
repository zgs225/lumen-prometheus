<?php

namespace Prometheus\Metrics\Stores;

use Illuminate\Redis\RedisManager;
use Prometheus\Contracts\Store;
use Prometheus\Metrics\MetricFamilySamples;
use Prometheus\Metrics\Sample;
use Prometheus\Metrics\Type;
use Prometheus\Supports\LaravelRedisSpinLock;

class Counter extends Base implements \Prometheus\Contracts\Metrics\Counter
{
    protected $value;

    public function __construct(Store $store, RedisManager $redis, $namespace, $subsystem, $name, $helper, array $labels)
    {
        parent::__construct($store, $namespace, $subsystem, $name, $helper, $labels);
        $this->lock  = new LaravelRedisSpinLock($this->getIdentifier(), $redis);
    }

    /**
     * 获取测量的数据
     *
     * @return array MetricFamilySamples数组
     */
    public function collect()
    {
        $this->lock->lock();
        $this->syncValue();
        $sample = new Sample($this->getFQName(), $this->getLabels(), $this->getValue());
        $family = new MetricFamilySamples($this->getFQName(), Type::COUNTER, $this->getHelp(), [$sample]);
        $this->lock->unlock();
        return [$family];
    }

    public function inc()
    {
        $this->add(1);
    }

    public function add($by)
    {
        if ($by < 0)
            throw new \InvalidArgumentException("值不能是负数");
        $this->lock->lock();
        $this->syncValue();
        $this->value += $by;
        $this->sync();
        $this->lock->unlock();
    }

    protected function syncValue()
    {
        $counter = $this->store->get($this->storeKey());
        if (!$counter)
            $this->value = 0;
        else
            $this->value = $counter->getValue();
    }

    public function getValue()
    {
        return $this->value;
    }
}
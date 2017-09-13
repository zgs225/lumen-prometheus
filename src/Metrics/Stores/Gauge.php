<?php

namespace Prometheus\Metrics\Stores;

use Illuminate\Contracts\Container\Container;
use Prometheus\Metrics\MetricFamilySamples;
use Prometheus\Metrics\Sample;
use Prometheus\Metrics\Type;

class Gauge extends Base implements \Prometheus\Contracts\Metrics\Gauge
{
    protected $value;

    public function __construct(Container $container, $namespace, $subsystem, $name, $helper, array $labels)
    {
        parent::__construct($container, $namespace, $subsystem, $name, $helper, $labels);
        $this->lock->lock();
        $this->syncValue();
        $this->sync();
        $this->lock->unlock();
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
        $family = new MetricFamilySamples($this->getFQName(), Type::GAUGE, $this->getHelp(), [$sample]);
        $this->lock->unlock();
        return [$family];
    }

    public function set($val)
    {
        $this->lock->lock();
        $this->syncValue();
        $this->value = $val;
        $this->sync();
        $this->lock->unlock();
    }

    public function inc()
    {
        $this->add(1);
    }

    public function dec()
    {
        $this->add(-1);
    }

    public function add($by)
    {
        $this->lock->lock();
        $this->syncValue();
        $this->value += $by;
        $this->sync();
        $this->lock->unlock();
    }

    public function sub($by)
    {
        $this->add(-$by);
    }

    public function setToCurrentTime()
    {
        $this->set(microtime(true));
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

    public function toArray()
    {
        return array_merge(parent::toArray(), ['value' => $this->getValue()]);
    }

    public function serialize()
    {
        return serialize($this->toArray());
    }

    public function unserialize($serialized)
    {
        parent::unserialize($serialized);
        $attributes  = unserialize($serialized);
        $this->value = $attributes['value'];
    }
}
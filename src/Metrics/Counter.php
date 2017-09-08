<?php

namespace Prometheus\Metrics;

use Prometheus\Contracts\Metrics\Counter as CounterContract;

class Counter extends Base implements CounterContract
{
    protected $value = 0;

    public function __construct($namespace, $subsystem, $name, $helper, array $labels)
    {
        parent::__construct($namespace, $subsystem, $name, $helper, $labels);
    }

    public function collect()
    {
        // TODO: Implement collect() method.
    }

    public function inc()
    {
        $this->add(1);
    }

    public function add($by)
    {
        if ($by < 0)
            throw new \InvalidArgumentException("值不能是复数");
        $this->value += $by;
    }
}
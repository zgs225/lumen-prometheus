<?php

namespace Prometheus\Builders;

use Prometheus\Metrics\Stores\Counter;

class CounterBuilder extends BaseBuilder
{
    /**
     * @return mixed
     */
    public function build()
    {
        $counter = new Counter($this->container, $this->namespace, $this->subsystem, $this->name, $this->help, $this->labels);
        $this->registry->register($counter);
        return $counter;
    }
}
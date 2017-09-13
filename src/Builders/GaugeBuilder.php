<?php

namespace Prometheus\Builders;

use Prometheus\Metrics\Stores\Gauge;

class GaugeBuilder extends BaseBuilder
{

    /**
     * @return \Prometheus\Contracts\Metrics\Gauge
     */
    public function build()
    {
        $metric = new Gauge($this->container, $this->namespace, $this->subsystem, $this->name, $this->help, $this->labels);
        $this->registry->register($metric);
        return $metric;
    }
}
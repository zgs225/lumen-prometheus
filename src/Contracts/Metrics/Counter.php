<?php

namespace Prometheus\Contracts\Metrics;

use Prometheus\Contracts\Collector;
use Prometheus\Contracts\Metric;

interface Counter extends Metric, Collector
{
    public function inc();

    public function add($by);
}
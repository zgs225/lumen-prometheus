<?php

namespace Prometheus\Contracts\Metrics;

use Prometheus\Contracts\Collector;
use Prometheus\Contracts\Metric;

interface Gauge extends Metric, Collector
{
    public function set($val);

    public function inc();

    public function dec();

    public function add($by);

    public function sub($by);

    public function setToCurrentTime();
}
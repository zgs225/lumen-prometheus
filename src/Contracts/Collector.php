<?php

namespace Prometheus\Contracts;


interface Collector
{
    public function collect();
}
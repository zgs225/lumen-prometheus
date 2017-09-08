<?php

namespace Prometheus\Contracts;

use Prometheus\Metrics\Base;

interface Registry
{
    public function register(Base $metric);

    public function unregister(Base $metric);
}
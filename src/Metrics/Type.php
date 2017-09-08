<?php

namespace Prometheus\Metrics;


class Type
{
    const COUNTER   = 1;
    const GAUGE     = 2;
    const SUMMARY   = 3;
    const HISTOGRAM = 4;
    const UNTYPED   = 5;
}
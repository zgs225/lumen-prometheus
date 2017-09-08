<?php

namespace Prometheus\Exceptions;

use Exception;

class DuplicatedMetricException extends Exception
{
    /**
     * @var \Prometheus\Contracts\Metric
     */
    protected $metric;

    /**
     * DuplicatedMetricException constructor.
     * @param \Prometheus\Contracts\Metric $metric
     */
    public function __construct(\Prometheus\Contracts\Metric $metric)
    {
        $this->metric  = $metric;
        $this->message = sprintf('统计项(%s)已经存在: ', (string) $metric);
    }
}
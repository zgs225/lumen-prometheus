<?php

namespace Prometheus\Registry;

use Prometheus\Contracts\Registry;
use Prometheus\Exceptions\DuplicatedMetricException;
use Prometheus\Metrics\Base;

class BaseRegistry implements Registry
{
    protected $name;

    /**
     * id => metric map
     *
     * @var array
     */
    protected $container = [];

    /**
     * BaseRegistry constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return Registry
     */
    public static function defaultRegistry()
    {
        return new static("_default");
    }

    public function register(Base $metric)
    {
        if (isset($this->container[$metric->getIdentifier()]))
            throw new DuplicatedMetricException($metric);
        $this->container[$metric->getIdentifier()] = $metric;
    }

    public function unregister(Base $metric)
    {
        unset($this->container[$metric->getIdentifier()]);
    }
}
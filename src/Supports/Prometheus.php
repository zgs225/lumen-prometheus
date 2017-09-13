<?php

namespace Prometheus\Supports;

use Illuminate\Contracts\Container\Container;
use Prometheus\Builders\CounterBuilder;
use Prometheus\Contracts\Registry;

class Prometheus
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Prometheus constructor.
     * @param $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return \Prometheus\Contracts\Builder
     */
    public function counter()
    {
        $builder  = new CounterBuilder();
        $registry = $this->container->make(Registry::class);
        return $builder->setContainer($this->container)->setRegistry($registry);
    }
}
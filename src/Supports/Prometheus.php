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
     * @var Registry
     */
    protected $registry;

    /**
     * Prometheus constructor.
     * @param $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->registry  = $container->make(Registry::class);
    }

    /**
     * @return \Prometheus\Contracts\Builder
     */
    public function counter()
    {
        $builder  = new CounterBuilder();
        return $builder->setContainer($this->container)->setRegistry($this->registry);
    }
}
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
     * @var \Closure
     */
    protected $bridgeResolver;

    /**
     * @var \Prometheus\Contracts\Bridge
     */
    protected $bridge;

    /**
     * Prometheus constructor.
     * @param $container
     */
    public function __construct(Container $container, \Closure $bridgeResolver)
    {
        $this->container      = $container;
        $this->registry       = $container->make(Registry::class);
        $this->bridgeResolver = $bridgeResolver;
    }

    /**
     * @return \Prometheus\Contracts\Builder
     */
    public function counter()
    {
        $builder  = new CounterBuilder();
        return $builder->setContainer($this->container)->setRegistry($this->registry);
    }

    public function bridge()
    {
        if (!$this->bridge) {
            $this->bridge = call_user_func($this->bridgeResolver, $this->registry);
        }
        return $this->bridge->bridge();
    }
}
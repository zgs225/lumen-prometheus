<?php

namespace Prometheus\Builders;

use Illuminate\Contracts\Container\Container;
use Prometheus\Contracts\Builder;
use Prometheus\Contracts\Registry;

abstract class BaseBuilder implements Builder
{
    protected $namespace;

    protected $subsystem;

    protected $name;

    protected $help;

    protected $labels = [];

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Registry
     */
    protected $registry;

    public function _namespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function subsystem($subsystem)
    {
        $this->subsystem = $subsystem;
        return $this;
    }

    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function help($help)
    {
        $this->help = $help;
        return $this;
    }

    public function labels(array $labels)
    {
        $this->labels = $labels;
        return $this;
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
        return $this;
    }

    public function setRegistry(Registry $registry)
    {
        $this->registry = $registry;
        return $this;
    }
}
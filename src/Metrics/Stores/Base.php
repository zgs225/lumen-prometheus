<?php

namespace Prometheus\Metrics\Stores;

use Prometheus\Contracts\Lock;
use Prometheus\Contracts\Store;

abstract class Base extends \Prometheus\Metrics\Base
{
    /**
     * @var \Prometheus\Contracts\Store
     */
    protected $store;

    /**
     * @var \Prometheus\Contracts\Lock
     */
    protected $lock;

    public function __construct(Store $store, Lock $lock, $namespace, $subsystem, $name, $helper, array $labels)
    {
        $this->store = $store;
        $this->lock  = $lock;
        parent::__construct($namespace, $subsystem, $name, $helper, $labels);
    }
}
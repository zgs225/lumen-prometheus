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

    public function __construct(Store $store, $namespace, $subsystem, $name, $helper, array $labels)
    {
        parent::__construct($namespace, $subsystem, $name, $helper, $labels);
        $this->store = $store;
    }


    protected function metricKey()
    {
        return 'metric:'.$this->getIdentifier();
    }

    protected function sync()
    {
        $this->store->put($this->metricKey(), $this);
    }
}
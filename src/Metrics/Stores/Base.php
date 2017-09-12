<?php

namespace Prometheus\Metrics\Stores;

use Illuminate\Container\Container;
use Prometheus\Contracts\Store;
use Prometheus\Supports\LaravelRedisSpinLock;

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


    public function storeKey()
    {
        return 'metric:'.$this->getIdentifier();
    }

    protected function sync()
    {
        $this->store->put($this->storeKey(), $this);
    }

    function __wakeup()
    {
        $app   = Container::getInstance();
        $redis = $app->make('redis');
        $this->store = $app->make(Store::class);
        $this->lock  = new LaravelRedisSpinLock($this->getIdentifier(), $redis);
    }
}
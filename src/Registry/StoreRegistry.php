<?php

namespace Prometheus\Registry;


use Illuminate\Container\Container;
use Prometheus\Contracts\Lock;
use Prometheus\Contracts\Store;
use Prometheus\Exceptions\DuplicatedMetricException;
use Prometheus\Metrics\Base;
use Prometheus\Stores\LaravelRedis;
use Prometheus\Supports\LaravelRedisSpinLock;

class StoreRegistry extends BaseRegistry
{
    /**
     * @var \Prometheus\Contracts\Store
     */
    protected $store;

    /**
     * @var \Prometheus\Contracts\Lock
     */
    protected $lock;

    public function __construct($name, Store $store, Lock $lock)
    {
        parent::__construct($name);
        $this->store = $store;
        $this->lock  = $lock;
    }

    public static function defaultRegistry()
    {
        $name  = '_default';
        $redis = Container::getInstance()->make('redis');
        $lock  = new LaravelRedisSpinLock('lock:registry:'.$name, $redis);
        $store = new LaravelRedis($redis);
        return new static($name, $store, $lock);
    }

    public function register(Base $metric)
    {
        $this->lock->lock();
        $this->syncContainers();
        if (isset($this->container[$metric->getIdentifier()])) {
            $this->lock->unlock();
            return;
        }
        $this->container[$metric->getIdentifier()]= true;
        $this->sync();
        $this->lock->unlock();
    }

    public function unregister(Base $metric)
    {
        $this->lock->lock();
        $this->syncContainers();
        unset($this->container[$metric->getIdentifier()]);
        $this->sync();
        $this->lock->unlock();
    }

    public function all()
    {
        $metrics = [];
        $this->lock->lock();
        $this->syncContainers();
        foreach($this->container as $identifier => $val) {
            $key    = 'metric:'.$identifier;
            $metric = $this->store->get($key);
            if ($metric)
                $metrics []= $metric;
        }
        $this->lock->unlock();
        return $metrics;
    }

    protected function syncContainers()
    {
        $container = $this->store->get($this->storeKey());
        if (!$container)
            $container = [];
        $this->container = $container;
    }


    protected function sync()
    {
        $this->store->put($this->storeKey(), $this->container);
    }

    protected function storeKey()
    {
        return 'registry:'.$this->name;
    }
}

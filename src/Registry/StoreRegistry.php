<?php

namespace Prometheus\Registry;


use Illuminate\Container\Container;
use Prometheus\Contracts\Store;
use Prometheus\Metrics\Base;
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

    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    public function __construct(\Illuminate\Contracts\Container\Container $container, $name)
    {
        parent::__construct($name);
        $this->container = $container;
        $this->init();
        $this->lock->lock();
        $this->syncContainers();
        $this->sync();
        $this->lock->unlock();
    }

    protected function init()
    {
        $this->store = $this->container->make(Store::class);
        $this->lock  = new LaravelRedisSpinLock('registry:'.$this->name, $this->container->make('redis'));
    }

    /**
     * @return StoreRegistry
     */
    public static function defaultRegistry()
    {
        $name  = '_default';
        return new static(Container::getInstance(), $name);
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

    public function clear()
    {
        $this->lock->lock();
        $this->container = [];
        $this->sync();
        $this->lock->unlock();
    }
}

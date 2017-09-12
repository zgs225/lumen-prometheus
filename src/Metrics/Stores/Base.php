<?php

namespace Prometheus\Metrics\Stores;

use Illuminate\Container\Container;
use Illuminate\Contracts\Support\Arrayable;
use Prometheus\Contracts\Store;
use Prometheus\Supports\LaravelRedisSpinLock;

abstract class Base extends \Prometheus\Metrics\Base implements \Serializable, Arrayable
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
    protected $application;

    public function __construct(\Illuminate\Contracts\Container\Container $application, $namespace, $subsystem, $name, $helper, array $labels)
    {
        parent::__construct($namespace, $subsystem, $name, $helper, $labels);
        $this->application = $application;
        $this->init();
    }


    public function storeKey()
    {
        return 'metric:'.$this->getIdentifier();
    }

    protected function sync()
    {
        $this->store->put($this->storeKey(), $this);
    }

    public function toArray()
    {
        return [
            'id'     => $this->getIdentifier(),
            'fqName' => $this->getFQName(),
            'help'   => $this->getHelp(),
            'labels' => $this->getLabels()
        ];
    }

    function __wakeup()
    {
        $app = Container::getInstance();
        $this->application = $app;
        $this->init();
    }

    protected function init()
    {
        $this->store = $this->application->make(Store::class);
        $this->lock  = new LaravelRedisSpinLock($this->getIdentifier(), $this->application->make('redis'));
    }

    public function unserialize($serialized)
    {
        $attributes   = unserialize($serialized);
        $this->id     = $attributes['id'];
        $this->fqName = $attributes['fqName'];
        $this->help   = $attributes['help'];
        $this->labels = $attributes['labels'];
        $this->__wakeup();
    }
}
<?php

namespace Prometheus\Stores;

use Illuminate\Redis\RedisManager;
use Prometheus\Contracts\Store;

class LaravelRedis implements Store
{
    /**
     * @var \Illuminate\Redis\RedisManager
     */
    protected $redis;

    /**
     * LaravelRedis constructor.
     * @param \Illuminate\Redis\RedisManager
     */
    public function __construct(RedisManager $redis)
    {
        $this->redis = $redis;
    }


    public function put($key, $val)
    {
        $this->redis->set($key, serialize($val));
    }

    public function get($key)
    {
        $val = $this->redis->get($key);
        if (is_null($val))
            return $val;
        return unserialize($val);
    }
}
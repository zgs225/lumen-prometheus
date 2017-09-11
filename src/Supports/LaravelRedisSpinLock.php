<?php

namespace Prometheus\Supports;


use Illuminate\Redis\RedisManager;
use Prometheus\Contracts\Lock;

class LaravelRedisSpinLock implements Lock
{
    const MAX_LOCK_SECONDS = 300;

    protected $id;

    /**
     * @var \Illuminate\Redis\RedisManager
     */
    protected $redis;

    /**
     * LaravelRedisSpinLock constructor.
     * @param $id
     * @param \Illuminate\Redis\Connections\PhpRedisConnection $redis
     */
    public function __construct($id, RedisManager $redis)
    {
        $this->id    = $id;
        $this->redis = $redis;
    }


    public function lock()
    {
        while(true) {
            if ($this->redis->setnx($this->lockKey(), 1))
                $this->redis->expire($this->lockKey(), static::MAX_LOCK_SECONDS);
                break;
        }
    }

    public function unlock()
    {
        $this->redis->del($this->lockKey());
    }

    protected function lockKey()
    {
        return 'lock:'.$this->id;
    }
}
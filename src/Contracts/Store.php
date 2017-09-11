<?php

namespace Prometheus\Contracts;


/**
 * Interface Store
 * @package Prometheus\Contracts
 *
 * 用于保存各种统计项以及Registry
 */
interface Store
{
    /**
     * @param $key
     * @param $val
     * @return mixed
     */
    public function put($key, $val);

    /**
     * @param $key
     * @return mixed
     */
    public function get($key);
}
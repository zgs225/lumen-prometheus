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
    public function put($key, $val);

    public function get($key);
}
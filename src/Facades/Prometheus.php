<?php

namespace Prometheus\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class Prometheus
 * @package Prometheus\Facades
 *
 * @method static \Prometheus\Builders\CounterBuilder counter()
 */
class Prometheus extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Prometheus\Supports\Prometheus::class;
    }
}
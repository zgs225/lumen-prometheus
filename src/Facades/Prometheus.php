<?php

namespace Prometheus\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class Prometheus
 * @package Prometheus\Facades
 *
 * @method static \Prometheus\Builders\CounterBuilder counter()
 * @method static string bridge()
 */
class Prometheus extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Prometheus\Supports\Prometheus::class;
    }
}
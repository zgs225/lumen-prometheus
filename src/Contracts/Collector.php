<?php

namespace Prometheus\Contracts;


interface Collector
{
    /**
     * 获取测量的数据
     *
     * @return array MetricFamilySamples数组
     */
    public function collect();
}
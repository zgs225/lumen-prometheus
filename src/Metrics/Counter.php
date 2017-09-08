<?php

namespace Prometheus\Metrics;

use Prometheus\Contracts\Metrics\Counter as CounterContract;

class Counter implements CounterContract
{

    public function collect()
    {
        // TODO: Implement collect() method.
    }

    public function inc()
    {
        // TODO: Implement inc() method.
    }

    public function add($by)
    {
        // TODO: Implement add() method.
    }

    /**
     * 全名由 Namespace, Subsystem和Name组成
     *
     * @return string
     */
    public function getFQName()
    {
        // TODO: Implement getFQName() method.
    }

    /**
     * 获取描述信息
     *
     * @return string
     */
    public function getHelp()
    {
        // TODO: Implement getHelp() method.
    }

    /**
     * 获取标签名称, 值映射
     *
     * @return array
     */
    public function getLabels()
    {
        // TODO: Implement getLabels() method.
    }

    /**
     * 获取唯一标识ID, 由fqName和Labels组成的字符串hash后得到
     *
     * @return int
     */
    public function getIdentifier()
    {
        // TODO: Implement getIdentifier() method.
    }
}
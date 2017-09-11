<?php

namespace Prometheus\Contracts;

interface Metric
{
    /**
     * 全名由 Namespace, Subsystem和Name组成
     *
     * @return string
     */
    public function getFQName();

    /**
     * 获取描述信息
     *
     * @return string
     */
    public function getHelp();

    /**
     * 获取标签名称, 值映射
     *
     * @return array
     */
    public function getLabels();

    /**
     * 获取唯一标识ID, 由fqName和Labels组成的字符串hash后得到
     *
     * @return string
     */
    public function getIdentifier();
}
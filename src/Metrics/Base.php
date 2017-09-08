<?php

namespace Prometheus\Metrics;

use Prometheus\Contracts\Collector;
use Prometheus\Contracts\Metric;

abstract class Base implements Collector, Metric
{
    protected $fqName;

    protected $helper;

    protected $labels = [];

    protected $id;

    /**
     * Base constructor.
     * @param $namespace
     * @param $subsystem
     * @param $name
     * @param $helper
     * @param array $labels
     */
    public function __construct($namespace, $subsystem, $name, $helper, array $labels)
    {
        $this->buildFQName($namespace, $subsystem, $name);
        $this->setLabels($labels);
        $this->generateID();
        $this->helper = $helper;
    }

    public function getFQName()
    {
        $this->fqName;
    }

    public function getHelp()
    {
        return $this->helper;
    }

    public function getLabels()
    {
        return $this->labels;
    }

    public function getIdentifier()
    {
        return $this->id;
    }


    protected function generateID()
    {
        $data = $this->fqName;
        if (!empty($this->labels)) {
            $first = true;
            $data .= '{';
            foreach($this->labels as $key => $val) {
                if (!$first) {
                    $data .= ',';
                }
                $data .= $key.':'.$val;
                $first = false;
            }
            $data .= '}';
        }
        $hash = hash('fnv164', $data);
        $this->id = hexdec($hash);
    }

    protected function setLabels($labels)
    {
        foreach($labels as $key => $val) {
            if (!isValidLabelName($key))
                throw new \InvalidArgumentException("标签名称不正确: " . $key);
        }
        ksort($labels);
        $this->labels = $labels;
    }

    protected function buildFQName($namespace, $subsystem, $name)
    {
        $fqName = '';
        if ($namespace)
            $fqName .= $namespace.'_';
        if ($subsystem)
            $fqName .= $subsystem.'_';
        if ($name)
            $fqName .= $name;

        if (!isValidMetricName($fqName))
            throw new \InvalidArgumentException("统计项名称不正确: ".$fqName);

        $this->fqName = $fqName;
    }
}
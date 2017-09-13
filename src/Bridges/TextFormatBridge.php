<?php

namespace Prometheus\Bridges;

use Prometheus\Contracts\Bridge;
use Prometheus\Contracts\Registry;
use Prometheus\Metrics\MetricFamilySamples;
use Prometheus\Metrics\Sample;

class TextFormatBridge implements Bridge
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * TextFormatBridge constructor.
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }


    public function bridge()
    {
        $text           = '';
        $metricFamilies = $this->getAndMergeAllMetricFamilies();

        foreach($metricFamilies as $family)
        {
            $text .= $this->bridgeMetricFamily($family);
        }

        return $text;
    }

    /**
     * 从 Registry 中获取所有的MetricFamily, 并且将名称相同的MetricFamily合并
     */
    protected function getAndMergeAllMetricFamilies()
    {
        $collectors     = $this->registry->all();
        $metricFamilies = [];
        foreach ($collectors as $collector) {
            $families = $collector->collect();
            array_push($metricFamilies, ...$families);
        }
        return $this->mergeAllMetricFamilies($metricFamilies);
    }

    protected function mergeAllMetricFamilies(array $families)
    {
        if (count($families) == 0)
            return [];
        $result = [];
        foreach ($families as $family) {
            $key = $family->getName();
            if (!isset($result[$key]))
                $result[$key] = $family;
            else {
                $result[$key]->pushSamples(...$family->getSamples());
            }
        }
        sort($result);
        return array_values($result);
    }

    protected function bridgeMetricFamily(MetricFamilySamples $familySamples)
    {
        $text = $this->headerOfMetricFamily($familySamples);
        foreach($familySamples->getSamples() as $sample) {
            $text .= $this->bridgeSample($sample);
        }
        return $text;
    }

    protected function bridgeSample(Sample $sample)
    {
        return sprintf("%s%s %s\n", $sample->getName(), $sample->getLabelsText(), $sample->getValueText());
    }

    protected function headerOfMetricFamily(MetricFamilySamples $familySamples)
    {
        $text = sprintf(
            "# HELP %s %s\n# TYPE %s %s\n",
            $familySamples->getName(),
            escapeString($familySamples->getHelp()),
            $familySamples->getName(),
            $familySamples->getTypeString()
        );
        return $text;
    }
}
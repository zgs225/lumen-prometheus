<?php

namespace Prometheus\Bridges;

use Prometheus\Contracts\Bridge;
use Prometheus\Contracts\Collector;
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
        $collectors = $this->registry->all();
        $text       = '';
        foreach($collectors as $collector) {
            $text .= $this->bridgeCollector($collector);
        }
    }

    protected function bridgeCollector(Collector $collector)
    {
        $text = '';
        
        foreach($collector->collect() as $metricFamily) {
            $text .= $this->bridgeMetricFamily($metricFamily);
        }
        
        return $text;
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
        return sprintf("%s%s %s", $sample->getName(), $sample->getLabelsText(), $sample->getValueText());
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
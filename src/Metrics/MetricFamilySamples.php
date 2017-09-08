<?php

namespace Prometheus\Metrics;

class MetricFamilySamples implements \JsonSerializable
{
    protected $name;

    protected $type;

    protected $help;

    protected $samples = [];

    /**
     * MetricFamilySamples constructor.
     * @param $name
     * @param $type
     * @param $help
     * @param array $samples
     */
    public function __construct($name, $type, $help, array $samples)
    {
        $this->name    = $name;
        $this->type    = $type;
        $this->help    = $help;
        $this->samples = $samples;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * @return array
     */
    public function getSamples()
    {
        return $this->samples;
    }

    function jsonSerialize()
    {
        return [
            'name'    => $this->name,
            'type'    => $this->type,
            'help'    => $this->help,
            'samples' => $this->samples
        ];
    }
}
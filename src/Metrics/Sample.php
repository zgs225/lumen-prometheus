<?php

namespace Prometheus\Metrics;

use JsonSerializable;

class Sample implements JsonSerializable
{
    protected $name;

    protected $labels;

    protected $value;

    /**
     * Sample constructor.
     * @param $name
     * @param $labels
     * @param $value
     */
    public function __construct($name, $labels, $value)
    {
        $this->name   = $name;
        $this->labels = $labels;
        $this->value  = $value;
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
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    function __toString()
    {
        $data = $this->name;
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
        $data .= ' ' . $this->value;
        return $data;
    }

    function jsonSerialize()
    {
        return [
            'name'   => $this->name,
            'value'  => $this->value,
            'labels' => $this->labels
        ];
    }

    public function getValueText()
    {
        if (is_nan($this->value))
            return "NaN";
        if (is_infinite($this->value))
            return "+Inf";
        return (string) $this->value;
    }

    public function getLabelsText()
    {
        if (count($this->labels) == 0)
            return '';
        $text  = '{';
        $first = true;
        foreach($this->labels as $key => $val) {
            if (!$first)
                $text .= ',';
            $text .= sprintf('%s="%s"', $key, escapeString($val));
            $first = false;
        }
        $text .= '}';
        return $text;
    }
}

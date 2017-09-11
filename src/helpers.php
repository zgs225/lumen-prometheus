<?php

use Illuminate\Support\Str;

if (!function_exists('isValidMetricName')) {
    function isValidMetricName($name)
    {
        if (strlen($name) == 0)
            return false;
        foreach(str_split($name) as $i => $b) {
            if (!(($b >= 'a' && $b <= 'z') || ($b >= 'A' && $b <= 'Z') || $b == '_' || $b == ':' || ($b >= '0' && $b <= '9' && $i > 0))) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists('isValidLabelName')) {
    define('_RESERVE_LABEL_PREFIX', '__');

    function isValidLabelName($label)
    {
        if (strlen($label) == 0)
            return false;

        if (Str::startsWith($label, _RESERVE_LABEL_PREFIX))
            return false;

        foreach(str_split($label) as $i => $b) {
            if (!(($b >= 'a' && $b <= 'z') || ($b >= 'A' && $b <= 'Z') || $b == '_' || ($b >= '0' && $b <= '9' && $i > 0))) {
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('escapeString')) {
    function escapeString($text)
    {
        return preg_replace_callback("[\"|\\\\|\\\n]", function($matches) {
            return '\\'.$matches[0];
        }, $text);
    }
}

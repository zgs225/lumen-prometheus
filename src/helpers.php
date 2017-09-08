<?php


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

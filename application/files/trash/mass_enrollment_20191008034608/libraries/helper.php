<?php

function translate($key, $language,$echo = true)
{
    $val = Config::get('mass_enrollment::'. $language. '.'. $key);
    if (empty($val)) {
        $val = Config::get('mass_enrollment::langkeys.'. $key);
    }
    if ($echo) {
        echo $val;
    } else {
        return $val;
    }
}
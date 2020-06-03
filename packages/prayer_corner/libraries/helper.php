<?php

function translatepc($key, $language, $echo = true)
{
    $val = Config::get('prayer_corner::'. $language. '.'. $key);
    if (empty($val)) {
        $val = Config::get('prayer_corner::langkeys.'. $key);
    }
    if ($echo) {
        echo $val;
    } else {
        return $val;
    }
}
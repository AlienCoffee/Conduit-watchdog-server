<?php

    $config_filepath__ = "configs/constants.json";

    $config_map__ = json_decode (file_get_contents ($config_filepath__), true);
    $config_map__ = flat_map ($config_map__, "");
    
    foreach ($config_map__ as $key => $value) {
        define (strtoupper ($key), $value);
    }

    function flat_map ($map, $prefix) {
        $result_map = Array ();
        foreach ($map as $k => $v) {
            $key = ($prefix ? $prefix."_" : "") . $k;

            if (is_array ($v)) {
                foreach (flat_map ($v, $key) as $k2 => $v2) {
                    $result_map [$k2] = $v2;
                }
            } else {
                $result_map [$key] = $v;
            }
        }

        return $result_map;
    }

?>
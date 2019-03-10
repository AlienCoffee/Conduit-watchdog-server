<?php

class WatchdogController {

    public function handleConsolePages (
        $context,
        $paths      = ["/watchdog/tasklist"],
        $method     = "POST",
        $authorized = true
    ) {
        $data = json_decode ($context ["data"], true);
        $platform = $data ["platform"];

        $script_file = get_script_file ($data ["platform"], "tasklist");
        $output_lines = [];
        if (str_compare ($platform, "windows")) {
            exec ("\"$script_file\"", $output_lines);
        }
        
        return json_encode ($output_lines);
    }

}

?>
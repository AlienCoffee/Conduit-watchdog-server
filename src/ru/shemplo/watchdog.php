<?php

class WatchdogController {

    public function handleWatchdogUpdate (
        $context,
        $paths      = ["/watchdog/update"],
        $method     = "POST",
        $authorized = true
    ) {
        $file_key = array_keys ($_FILES) [0]; // select only first file
        $file = $_FILES [$file_key];
        if (!in_array (get_file_extension ($file ["name"]), ["zip"])) {
            return new Verdict (false, "Wrong file extension");
        }

        move_uploaded_file ($file ["tmp_name"], $file ["name"]);
        unzip ($file ["name"]); unlink ($file ["name"]);
        
        return new Verdict (true, "success");
    }

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
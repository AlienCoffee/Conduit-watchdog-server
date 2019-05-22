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

        $buildfile = fopen ("configs/build.info", "w+t");
        $time = date ("d.m.Y H:i:s", $context ["time"]);
        fwrite ($buildfile, "Build version from <u>$time</u>");
        fclose ($buildfile);
        
        return new Verdict (true, "success");
    }

    public function handleWatchdogScripts (
        $context,
        $paths      = ["/watchdog/scripts"],
        $method     = "GET",
        $authorized = true
    ) {
        return json_encode (Array (
            "verdict" => true,
            "message" => "",
            "scripts" => get_files_and_folders (SERVER_SCRIPTS) ["files"]
        ));        
    }

    public function handleWatchdogScriptsUpload (
        $context,
        $paths      = ["/watchdog/scripts"],
        $method     = "POST",
        $authorized = true
    ) {
        $file_key = array_keys ($_FILES) [0]; // select only first file
        $file = $_FILES [$file_key];
        $extension = get_file_extension ($file ["name"]);
        if (!in_array ($extension, ["cmd", "sh"])) {
            return new Verdict (false, "Wrong file extension");
        }

        move_uploaded_file ($file ["tmp_name"], SERVER_SCRIPTS.$file ["name"]);
        
        if (file_exists("configs/scripts.json")){
            $scripts = json_decode(file_get_contents("configs/scripts.json"), true);               
        } else {
            return new Verdict (false, "Unknown location for scripts.json");
        }        

        if ($extension == "cmd") {
            $scripts['windows']['tasklist'] = $file ["name"];
        } else {
            $scripts['linux']['tasklist'] = $file ["name"];
        }
        file_put_contents("configs/scripts.json", json_encode($scripts));

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
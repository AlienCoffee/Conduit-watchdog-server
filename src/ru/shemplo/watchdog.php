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

        $extension = pathinfo ($file ["name"], PATHINFO_EXTENSION);
        if (!in_array ($extension, ["cmd", "sh"])) {
            return new Verdict (false, "Wrong file extension");
        }

        move_uploaded_file ($file ["tmp_name"], SERVER_SCRIPTS.$file ["name"]);

        if (file_exists("configs/scripts.json")){
            $scripts = json_decode(file_get_contents("configs/scripts.json"), true);
        } else {
            return new Verdict (false, "Unknown location for scripts.json");
        }

        $filename = pathinfo ($file["name"], PATHINFO_FILENAME);
        $platform = ($extension == "cmd"? "windows": "linux");
        // Обеспечить поиск скрипта по базе данных
        $id = rand(1,717);
        // Есть ли скрипт в базе
        $find = find_script($platform, $file["name"]);

        $data = json_decode ($context ["data"], true);

        if ($find === false){
            $scripts[$platform][$id]['name'] = $data["filename"];
            $scripts[$platform][$id]['file'] = $file ["name"];
        } else{
            // Записываем с найденным идентификатором
            $id = $find["id"];
            $scripts[$platform][$id]['name'] = $data["filename"];
        }       

        file_put_contents("configs/scripts.json", json_encode($scripts));

        return new Verdict (true, "success");
    }

    public function handleWatchdogScriptsDelete (
        $context,
        $paths      = ["/watchdog/scripts/delete"],
        $method     = "POST",
        $authorized = true
    ) {
        $data = json_decode($context["data"], true);
        $script_id = $data["id"];
        $platform = $data["platform"];
        $find = find_script_by_id($platform, $script_id);
        if (!$find){
            return new Verdict (false, "Script not found");
        }
        delete_script($platform, $script_id);
        return new Verdict (true, "success");
    }

    public function handleWatchdogScriptsSource (
        $context,
        $paths      = ["/watchdog/scripts/source"],
        $method     = "POST",
        $authorized = true
    ) {
        $data = json_decode($context["data"], true);
        $script_id = $data["id"];
        $platform = $data["platform"];

        $txt = read_script_by_id($platform, $script_id);

        if ($txt === false){
            return new Verdict (false, "Script not found");
        }
        return new Verdict (true, $txt);
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
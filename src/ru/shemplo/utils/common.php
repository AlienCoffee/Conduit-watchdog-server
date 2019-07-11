<?php

    function str_compare ($str1, $str2) {
        if (mb_strlen ($str1) != mb_strlen ($str2)) {
            // Different length of the strings
            return false; 
        }

        if (mb_strlen ($str1) == 0) {
            // Both string are empty
            return true; 
        }

        return mb_strpos ($str1, $str2) !== false;
    }

    function encrypt ($login, $password) {
        $credits = "$login#$password";
        return hash_hmac ("sha256", $credits, SERVER_ACCOUNT_SECRET);
    }

    function try_authorize ($login, $password) {
        if (str_compare ($password, SERVER_ACCOUNT_PASSWORD)
                && str_compare ($login, SERVER_ACCOUNT_LOGIN)) {
            return encrypt ($login, $password);
        }

        return false;
    } 

    function get_script_file ($platform, $script) {// Возможно стоит переписать
        $content = file_get_contents ("configs/scripts.json");
        $data = json_decode ($content, true);
        return SERVER_SCRIPTS.$data [$platform][$script];
    }

    function get_file_name ($path) {
        $path = str_replace ("\\", "/", $path);
        $parts = explode ("/", $path);
        return $parts [count ($parts) - 1];
    }

    function get_file_extension ($filename) {
        $parts = explode (".", $filename);
        return $parts [count ($parts) - 1];
    }

    function unzip ($filepath) {
        $zip = new ZipArchive ();
        if (!@$zip->open ($filepath)) {
            throw new Exception ("Failed to read archive");
        }

        $pathinfo = pathinfo (realpath ($filepath), PATHINFO_DIRNAME);
        if (!@$zip->extractTo ($pathinfo)) {
            $zip->close ();
            throw new Exception ("Failed to unzip archive");
        } else { $zip->close (); }

    }

    function total_scripts_by_platform () {
        $files = get_files_and_folders (SERVER_SCRIPTS) ["files"];
        $counters = Array (
            "windows" => 0,
            "linux"   => 0
        );

        foreach ($files as $file) {
            $counters [$file ["platform"]] += 1;
        }

        return $counters;
    }

    function get_files_and_folders ($path) {
        $counters = Array (
            "files"   => Array (),
            "folders" => Array ()
        );

        $stack = [$path];
        while (count ($stack) != 0) {
            $dir = array_pop ($stack);
            
            if (!is_dir ($dir) && is_file ($dir)) {
                $extension = pathinfo($dir, PATHINFO_EXTENSION);
                // Найти в базе данных скриптов этот 
                $platform = ($extension === "cmd" ? "windows"
                    : ($extension === "sh" ? "linux" : "unknown"));

                $find = find_script($platform, pathinfo($dir, PATHINFO_BASENAME));
                $visible = ($find ? $find['name']: pathinfo($dir, PATHINFO_FILENAME));
                // Сохранять скрипт в базе данных корректно
                array_push ($counters ["files"], Array (
                    "id"        => $find["id"],
                    "name"      => pathinfo($dir, PATHINFO_FILENAME),
                    "visible"   => $visible,
                    "extension" => $extension,
                    "path"      => $dir,
                    "platform"  => $platform,
                    "created"  => filectime ($dir)
                ));
                continue;
            } else if (is_dir ($dir)) {
                array_push ($counters ["folders"], Array (
                    "name" => get_file_name ($dir),
                    "path" => $dir
                ));
                foreach (scandir ($dir) as $tmp_path) {
                    if ($tmp_path == "." || $tmp_path == "..") {
                        continue; // Prevent endless loop
                    }
                    array_push ($stack, $path.$tmp_path);
                }
            }
        }

        return $counters;
    }

    function get_scripts(){// Переписать
        $content = file_get_contents ("configs/scripts.json");
        $data = json_decode ($content, true);
        return SERVER_SCRIPTS.$data [$platform][$script];
    }

    function find_script($platform, $filename) {
        $content = file_get_contents ("configs/scripts.json"); 
        $data = json_decode ($content, true);
        // Вернуть идентификатор скрипта по имени
        $found_id = -1;
        if (isset($data[$platform])){
            foreach($data [$platform] as $id => $script) {
                if ($script['file'] == $filename){
                    $found_id = $id;
                    break;
                }
            }
        }
            
        if ($found_id != -1) {

            $script_path = SERVER_SCRIPTS.$data [$platform][$found_id]['file'];

            if (file_exists($script_path)){
                return Array(
                    "id"=> $found_id, 
                    "name"=>$data [$platform][$found_id]['name']
                );
            }
        }
        return false;
    }

    function find_script_by_id($platform, $script_id) {
        $content = file_get_contents ("configs/scripts.json"); 
        $data = json_decode ($content, true);
        
        return isset($data[$platform][$script_id]);       
    }

    function delete_script($platform, $script_id) {

        $content = file_get_contents ("configs/scripts.json"); 
        $data = json_decode ($content, true);
        $script = $data[$platform][$script_id];

        $filename = SERVER_SCRIPTS.$script["file"];

        unset($data[$platform][$script_id]);

        file_put_contents("configs/scripts.json", json_encode($data));
        unlink($filename);

        return true;
    }

    function read_script_by_id($platform, $script_id) {

        $content = file_get_contents ("configs/scripts.json");
        $data = json_decode ($content, true);
        $script = $data[$platform][$script_id];

        $filename = SERVER_SCRIPTS.$script["file"];

        if (file_exists($filename)) {
            return file_get_contents($filename);
        }
        
        return false;
    }

?>
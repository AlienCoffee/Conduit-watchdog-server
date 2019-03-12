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

    function get_script_file ($platform, $script) {
        $content = file_get_contents ("configs/scripts.json");
        $data = json_decode ($content, true);
        return SERVER_SCRIPTS.$data [$platform][$script];
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

        $pathinfo = pathinfo (realpath ($filepath), PATHINFO_DIRNAME)."/test";
        if (!@$zip->extractTo ($pathinfo)) {
            $zip->close ();
            throw new Exception ("Failed to unzip archive");
        } else { $zip->close (); }

    }

?>
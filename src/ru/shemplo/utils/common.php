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

?>
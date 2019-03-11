<?php

class MainController {

    public function handleConsolePages (
        $context,
        $paths      = ["/", "/(console)"],
        $method     = "GET",
        $authorized = false
    ) {
        if (!$context ["user"]["authorized"]) {
            return new PageHolder ("login", $context);
        }

        //$path = explode ("/", $context ["request"]["path"]);
        $page = str_replace ("/", "", $context ["request"]["path"]);
        if (strlen ($page) == 0) { $page = "console"; }
        return new PageHolder ($page, $context);
    }

    public function handleLoginAttempt (
        $context,
        $paths      = ["/watchdog/user/login"],
        $method     = "POST",
        $authorized = false
    ) {
        $data = json_decode ($context ["data"], true);

        $token = try_authorize ($data ["login"], $data ["password"]);
        if ($token) {
            setcookie ("session", $token, time () + 60 * 60 * 1, "/");
            return new Verdict (true, "");
        }

        return new Verdict (false, "Wrong login or password");
    }

}

?>